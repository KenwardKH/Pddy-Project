<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransactionLog;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
use App\Models\CancelledTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function status($type = 'delivery')
{
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus'])
        ->where(function ($query) {
            // Kondisi status "belum selesai" baik untuk deliveryStatus atau pickupStatus
            $query->whereHas('deliveryStatus', function ($q) {
                $q->where('status', '!=', 'Selesai');
                $q->where('status', '!=', 'menunggu pembayaran');
                $q->where('status', '!=', 'dibatalkan');
            })->orWhereHas('pickupStatus', function ($q) {
                $q->where('status', '!=', 'Selesai');
                $q->where('status', '!=', 'menunggu pembayaran');
                $q->where('status', '!=', 'dibatalkan');
            });
        });

    // Filter berdasarkan 'type' jika parameter diberikan
    if ($type === 'delivery') {
        $invoices = $invoices->whereHas('deliveryStatus');
    } elseif ($type === 'pickup') {
        $invoices = $invoices->whereHas('pickupStatus');
    }

    $invoices = $invoices->orderBy('InvoiceID', 'asc')->get();

    // Hitung `totalAmount` untuk setiap invoice
    $invoices->map(function ($invoice) {
        $invoice->totalAmount = $invoice->invoiceDetails->reduce(function ($carry, $detail) {
            $quantity = (int) $detail->Quantity; 
            $price = (float) $detail->price;
            return $carry + ($quantity * $price);
        }, 0);
        return $invoice;
    });

    return view('kasir.kasir_status', ['type' => $type, 'invoices' => $invoices]);
}


    public function nextStatus($id, Request $request)
    {
        $type = $request->type; // 'delivery' atau 'pickup'
        $status = null;

       // Ambil data kasir yang sedang login
        $kasir = Auth::user()->kasir; // Menggunakan relasi 'kasir'
        if (!$kasir) {
            return redirect()->back()->with('error', 'Kasir belum login atau data kasir tidak ditemukan.');
        }

        // Ambil informasi kasir
        $cashierId = $kasir->id_kasir ?? null; // ID dari tabel kasir
        $cashierName = $kasir->nama_kasir ?? null;

        if (!$cashierId || !$cashierName) {
            return redirect()->back()->with('error', 'Data kasir tidak valid.');
        }


        if ($type === 'delivery') {
            $orderStatus = DeliveryOrderStatus::where('invoice_id', $id)->first();
            if ($orderStatus) {
                $statusSequence = ['diproses', 'diantar', 'selesai'];
                $currentIndex = array_search($orderStatus->status, $statusSequence);
                $status = $statusSequence[min($currentIndex + 1, count($statusSequence) - 1)];
                $orderStatus->status = $status;
                $orderStatus->updated_by = $cashierId;
                $orderStatus->save();
            }
        } elseif ($type === 'pickup') {
            $orderStatus = PickupOrderStatus::where('invoice_id', $id)->first();
            if ($orderStatus) {
                $statusSequence = ['diproses', 'menunggu pengambilan', 'selesai'];
                $currentIndex = array_search($orderStatus->status, $statusSequence);
                $status = $statusSequence[min($currentIndex + 1, count($statusSequence) - 1)];
                $orderStatus->status = $status;
                $orderStatus->updated_by = $cashierId;
                $orderStatus->save();
            }
        }

        if ($status) {
            return redirect()->back()->with('success', 'Status berhasil diubah ke ' . $status);
        }

        return redirect()->back()->with('error', 'Pesanan tidak ditemukan atau sudah selesai.');
    }


    public function home()
    {
        // Ambil data invoice
        $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus'])->get();

        $OnlinePickupProcess= PickupOrderStatus::where('status', 'menunggu pembayaran')->count();
        $OnlineDeliveryProcess= DeliveryOrderStatus::where('status', 'menunggu pembayaran')->count();

        // Hitung status pesanan
        $pickupProcess= PickupOrderStatus::where('status', 'diproses')->count();
        $deliveryProcess= DeliveryOrderStatus::where('status', 'diproses')->count();
        $onlineProcess= $OnlinePickupProcess + $OnlineDeliveryProcess;
        $waitingForPickup = PickupOrderStatus::where('status', 'Menunggu Pengambilan')->count();
        $inProcess = $pickupProcess + $deliveryProcess;
        $onDelivery = DeliveryOrderStatus::where('status', 'diantar')->count();

        // Ambil data produk
        $productTypes = Product::count();

        return view('kasir.kasir_home', [
            'invoices' => $invoices,
            'waitingForPickup' => $waitingForPickup,
            'inProcess' => $inProcess,
            'onDelivery' => $onDelivery,
            'onlineProcess' => $onlineProcess,
            'productTypes' => $productTypes,
        ]);
    }
    public function stock()
    {
        $products = Product::with('pricing')->get();

        return view('kasir.kasir_stock_barang',[
            'products' => $products
        ]);
    }

    public function profile()
    {
        
        return view('kasir.kasir_profile');
    }

    public function getInvoiceDetails($id)
    {
        $invoice = Invoice::with(['invoiceDetails'])
            ->findOrFail($id);
        
        // Hitung totalAmount
        $totalAmount = $invoice->invoiceDetails->reduce(function ($carry, $detail) {
            return $carry + ($detail->Quantity * $detail->price);
        }, 0);
    
        // Map the invoice details
        $details = $invoice->invoiceDetails->map(function ($detail) {
            return [
                'product' => $detail->productName, // Mengambil langsung nama produk dari InvoiceDetail
                'price' => $detail->price,        // Mengambil harga dari InvoiceDetail
                'Quantity' => $detail->Quantity, // Mengambil jumlah dari InvoiceDetail
                'productImage' => $detail->productImage, // Mengambil gambar dari InvoiceDetail
                'productUnit' => $detail->productUnit, // Mengambil satuan dari InvoiceDetail
                'total' => $detail->Quantity * $detail->price, // Menghitung total
            ];
        });
    
        // Log data for debugging
        \Log::info(['details' => $details, 'totalAmount' => $totalAmount]);

        return response()->json([
            'details' => $details,
            'totalAmount' => $totalAmount, // Menyertakan totalAmount ke dalam respons
        ]);
    }

    public function riwayat()
{
    // Ambil semua invoice yang sudah selesai
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus', 'cancelledTransaction'])
        ->where(function ($query) {
            // Kondisi status "Selesai" atau "dibatalkan" untuk deliveryStatus atau pickupStatus
            $query->whereHas('deliveryStatus', function ($q) {
                $q->whereIn('status', ['Selesai']);
            })->orWhereHas('pickupStatus', function ($q) {
                $q->whereIn('status', ['Selesai']);
            });
        })
        ->orderBy('InvoiceID', 'asc')
        ->get();
    
    // Add totalAmount calculation for each invoice
    $invoices = $invoices->map(function ($invoice) {
        $invoice->totalAmount = $invoice->invoiceDetails->reduce(function ($carry, $detail) {
            $quantity = (int) $detail->Quantity; 
            $price = (float) $detail->price;
            return $carry + ($quantity * $price);
        }, 0);
        return $invoice;
    });

    return view('kasir.kasir_riwayat', compact('invoices'));
}

    public function riwayatBatal()
{
    // Ambil semua invoice yang sudah selesai
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus', 'cancelledTransaction'])
        ->where(function ($query) {
            // Kondisi status "Selesai" atau "dibatalkan" untuk deliveryStatus atau pickupStatus
            $query->whereHas('deliveryStatus', function ($q) {
                $q->whereIn('status', ['dibatalkan']);
            })->orWhereHas('pickupStatus', function ($q) {
                $q->whereIn('status', ['dibatalkan']);
            });
        })
        ->orderBy('InvoiceID', 'asc')
        ->get();
    
    // Add totalAmount calculation for each invoice
    $invoices = $invoices->map(function ($invoice) {
        $invoice->totalAmount = $invoice->invoiceDetails->reduce(function ($carry, $detail) {
            $quantity = (int) $detail->Quantity; 
            $price = (float) $detail->price;
            return $carry + ($quantity * $price);
        }, 0);
        return $invoice;
    });

    return view('kasir.kasir_riwayat_batal', compact('invoices'));
}

public function pembayaran()
{
    // Ambil semua invoice yang terkait dengan CustomerID
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus','payment'])
        ->where(function ($query) {
            // Kondisi status "belum selesai" baik untuk deliveryStatus atau pickupStatus
            $query->whereHas('deliveryStatus', function ($q) {
                $q->where('status', 'menunggu pembayaran');
            })->orWhereHas('pickupStatus', function ($q) {
                $q->where('status', 'menunggu pembayaran');
            });
        })
        ->orderBy('InvoiceID', 'asc')
        ->get();
    
    // Add totalAmount calculation for each invoice
    $invoices = $invoices->map(function ($invoice) {
        $invoice->totalAmount = $invoice->invoiceDetails->reduce(function ($carry, $detail) {
            $quantity = (int) $detail->Quantity; 
            $price = (float) $detail->price;
            return $carry + ($quantity * $price);
        }, 0);

        // Ambil created_at dari deliveryStatus atau pickupStatus
        $createdAt = $invoice->deliveryStatus->created_at ?? $invoice->pickupStatus->created_at;

        // Hitung lastPay (2 hari setelah created_at)
        if ($createdAt) {
            $invoice->lastPay = \Carbon\Carbon::parse($createdAt)->addDays(2);
        } else {
            $invoice->lastPay = null; // Fallback jika created_at tidak tersedia
        }
        return $invoice;
    });

    return view('kasir.kasir_pembayaran', compact('invoices'));
}

    public function konfirmasi($id, Request $request)
    {
        $invoice = Invoice::find($id);

        $type = $request->type; // 'delivery' atau 'pickup'
        $status = null;

       // Ambil data kasir yang sedang login
        $kasir = Auth::user()->kasir; // Menggunakan relasi 'kasir'
        if (!$kasir) {
            return redirect()->back()->with('error', 'Kasir belum login atau data kasir tidak ditemukan.');
        }

        // Ambil informasi kasir
        $cashierId = $kasir->id_kasir ?? null; // ID dari tabel kasir
        $cashierName = $kasir->nama_kasir ?? null;

        if (!$cashierId || !$cashierName) {
            return redirect()->back()->with('error', 'Data kasir tidak valid.');
        }

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
        }

        // Perbarui status sesuai dengan jenis pengantaran
        if ($invoice->type === 'delivery') {
            $deliveryStatus = $invoice->deliveryStatus;
            if ($deliveryStatus) {
                $deliveryStatus->status = 'Diproses';
                $deliveryStatus->updated_by = $cashierId;
                $deliveryStatus->save();
            }
        } elseif ($invoice->type === 'pickup') {
            $pickupStatus = $invoice->pickupStatus;
            if ($pickupStatus) {
                $pickupStatus->status = 'Diproses';
                $pickUpStatus->updated_by = $cashierId;
                $pickupStatus->save();
            }
        }

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function batal($id, Request $request)
{
    // Validasi alasan pembatalan
    $request->validate([
        'reason' => 'required|string|max:255',
    ]);

    $invoice = Invoice::find($id);

    if (!$invoice) {
        return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
    }

    // Ambil data kasir yang sedang login
    $kasir = Auth::user()->kasir; // Menggunakan relasi 'kasir'
    if (!$kasir) {
        return redirect()->back()->with('error', 'Kasir belum login atau data kasir tidak ditemukan.');
    }

    // Ambil informasi kasir
    $cashierId = $kasir->id_kasir ?? null; // ID dari tabel kasir
    $cashierName = $kasir->nama_kasir ?? null;

    if (!$cashierId || !$cashierName) {
        return redirect()->back()->with('error', 'Data kasir tidak valid.');
    }

    // Ambil alasan pembatalan
    $reason = $request->input('reason');

    // Perbarui status sesuai dengan jenis pengantaran
    if ($invoice->type === 'delivery') {
        $deliveryStatus = $invoice->deliveryStatus;
        if ($deliveryStatus) {
            $deliveryStatus->status = 'dibatalkan';
            $deliveryStatus->updated_by = $cashierId;
            $deliveryStatus->save();
        } else {
            return redirect()->back()->with('error', 'Status pengantaran tidak ditemukan.');
        }
    } elseif ($invoice->type === 'pickup') {
        $pickupStatus = $invoice->pickupStatus;
        if ($pickupStatus) {
            $pickupStatus->status = 'dibatalkan';
            $pickupStatus->updated_by = $cashierId;
            $pickupStatus->save();
        } else {
            return redirect()->back()->with('error', 'Status pengambilan tidak ditemukan.');
        }
    }

    // Catat pembatalan di tabel CancelledTransaction
    CancelledTransaction::create([
        'InvoiceId' => $invoice->InvoiceID,
        'cancellation_reason' => $reason,
        'cancelled_by' => 'cashier', // Pembatalan dilakukan oleh kasir
        'cancellation_date' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'), // Tanggal pembatalan
    ]);

    return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pesanan.');
        }
    }


}
