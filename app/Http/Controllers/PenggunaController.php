<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransactionLog;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CancelledTransaction;


class PenggunaController extends Controller
{
    public function home()
    {
        return view('pengguna.pengguna_home');
    }

    public function status()
{
    $userId = Auth::id();

    // Ambil CustomerID berdasarkan user_id
    $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

    if (!$customerId) {
        // Jika tidak ada CustomerID, tampilkan pesan error atau halaman kosong
        return view('pengguna.pengguna_status', ['invoices' => []]);
    }

    // Ambil semua invoice yang terkait dengan CustomerID
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus'])
        ->where('CustomerID', $customerId) // Filter berdasarkan CustomerID
        ->where(function ($query) {
            // Kondisi status "belum selesai" baik untuk deliveryStatus atau pickupStatus
            $query->whereHas('deliveryStatus', function ($q) {
                $q->where('status', '!=', 'Selesai');
                $q->where('status', '!=', 'menunggu pembayaran');
            })->orWhereHas('pickupStatus', function ($q) {
                $q->where('status', '!=', 'Selesai');
                $q->where('status', '!=', 'menunggu pembayaran');
            });
        })
        ->orderBy('InvoiceId', 'desc')
        ->get();
    
    // Add totalAmount calculation for each invoice
    $invoices = $invoices->map(function ($invoice) {
        $invoice->totalAmount = $invoice->invoiceDetails->reduce(function ($carry, $detail) {
            return $carry + ($detail->Quantity * $detail->price);
        }, 0);
        return $invoice;
    });


    return view('pengguna.pengguna_status', compact('invoices'));
}

    public function pembayaran()
{
    $userId = Auth::id();

    // Ambil CustomerID berdasarkan user_id
    $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

    if (!$customerId) {
        // Jika tidak ada CustomerID, tampilkan pesan error atau halaman kosong
        return view('pengguna.pengguna_status', ['invoices' => []]);
    }

    // Ambil semua invoice yang terkait dengan CustomerID
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus','payment'])
        ->where('CustomerID', $customerId) // Filter berdasarkan CustomerID
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
            return $carry + ($detail->Quantity * $detail->price);
        }, 0);

        $createdAt = $invoice->deliveryStatus->created_at ?? $invoice->pickupStatus->created_at;

        // Hitung lastPay (2 hari setelah created_at)
        if ($createdAt) {
            $invoice->lastPay = \Carbon\Carbon::parse($createdAt)->addDays(2);
        } else {
            $invoice->lastPay = null; // Fallback jika created_at tidak tersedia
        }

        return $invoice;
    });

    return view('pengguna.pengguna_pembayaran', compact('invoices'));
}

    public function bukti_transfer(Request $request)
    {
        $validated = $request->validate([
            'InvoiceID' => 'required|exists:invoices,InvoiceID',
            'AmountPaid' => 'required|numeric|min:0',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        try {
            // Ambil nama asli file
            $bukti_trf = $request->file('bukti_transfer');
            $imageName = time() . '_' . $bukti_trf->getClientOriginalName();
        
            // Tentukan path untuk menyimpan file
            $destinationPath = public_path('images/bukti_transfer');
            
            // Pindahkan file ke folder tujuan
            $bukti_trf->move($destinationPath, $imageName);

            // Simpan data pembayaran
            Payment::create([
                'InvoiceID' => $validated['InvoiceID'],
                'PaymentDate' => now(),
                'AmountPaid' => $validated['AmountPaid'],
                'PaymentImage' => $imageName,
            ]);

            return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


public function riwayat()
{
    $userId = Auth::id();

    // Ambil CustomerID berdasarkan user_id
    $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

    if (!$customerId) {
        // Jika tidak ada CustomerID, tampilkan pesan atau halaman kosong
        return view('pengguna.pengguna_riwayat', ['invoices' => []]);
    }

    // Ambil semua invoice yang sudah selesai
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus', 'cancelledTransaction'])
        ->where('CustomerID', $customerId) // Filter berdasarkan CustomerID
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
            return $carry + ($detail->Quantity * $detail->price);
        }, 0);
        return $invoice;
    });

    return view('pengguna.pengguna_riwayat', compact('invoices'));
}

public function riwayatBatal()
{
    $userId = Auth::id();

    // Ambil CustomerID berdasarkan user_id
    $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

    if (!$customerId) {
        // Jika tidak ada CustomerID, tampilkan pesan atau halaman kosong
        return view('pengguna.pengguna_riwayat_batal', ['invoices' => []]);
    }

    // Ambil semua invoice yang sudah selesai
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus', 'cancelledTransaction'])
        ->where('CustomerID', $customerId) // Filter berdasarkan CustomerID
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
            return $carry + ($detail->Quantity * $detail->price);
        }, 0);
        return $invoice;
    });

    return view('pengguna.pengguna_riwayat_batal', compact('invoices'));
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

    public function batal($id, Request $request)
{
    $invoice = Invoice::find($id);

    if (!$invoice) {
        return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
    }

    // Ambil alasan pembatalan
    $reason = $request->input('reason');

    // Perbarui status sesuai dengan jenis pengantaran
    if ($invoice->type === 'delivery') {
        $deliveryStatus = $invoice->deliveryStatus;
        if ($deliveryStatus) {
            $deliveryStatus->status = 'dibatalkan';
            $deliveryStatus->save();
        }
    } elseif ($invoice->type === 'pickup') {
        $pickupStatus = $invoice->pickupStatus;
        if ($pickupStatus) {
            $pickupStatus->status = 'dibatalkan';
            $pickupStatus->save();
        }
    }

    // Catat pembatalan di tabel CancelledTransaction
    CancelledTransaction::create([
        'InvoiceId' => $invoice->InvoiceID,
        'cancellation_reason' => $reason,
        'cancelled_by' => 'customer', // Pembatalan dilakukan oleh kasir
        'cancellation_date' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') // Tanggal pembatalan
    ]);

    return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
}

    



}
