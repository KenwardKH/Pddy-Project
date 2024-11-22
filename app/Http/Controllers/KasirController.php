<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransactionLog;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
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
            })->orWhereHas('pickupStatus', function ($q) {
                $q->where('status', '!=', 'Selesai');
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
        $data = [
            'onlineOrders' => 3,
            'unpaidOrders' => 3,
            'processingOrders' => 4,
            'stockRunningLow' => 3,
        ];
        return view('kasir.kasir_home', compact('data'));
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
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus'])
        ->where(function ($query) {
            // Kondisi status "Selesai" baik untuk deliveryStatus atau pickupStatus
            $query->whereHas('deliveryStatus', function ($q) {
                $q->where('status', 'Selesai');
            })->orWhereHas('pickupStatus', function ($q) {
                $q->where('status', 'Selesai');
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
