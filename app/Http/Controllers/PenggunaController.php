<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransactionLog;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;


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
            })->orWhereHas('pickupStatus', function ($q) {
                $q->where('status', '!=', 'Selesai');
            });
        })
        ->orderBy('InvoiceID', 'asc')
        ->get();

    return view('pengguna.pengguna_status', compact('invoices'));
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
    $invoices = Invoice::with(['invoiceDetails', 'deliveryStatus', 'pickupStatus'])
        ->where('CustomerID', $customerId) // Filter berdasarkan CustomerID
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

    return view('pengguna.pengguna_riwayat', compact('invoices'));
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
    



}
