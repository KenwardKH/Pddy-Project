<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionList;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data filter
        $customerName = $request->input('customerName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query dasar
        $query = TransactionList::where('OrderStatus', 'selesai');

        // Tambahkan filter berdasarkan nama kasir
        if ($customerName) {
            $query->where('CustomerName', 'LIKE', '%' . $customerName . '%');
        }

        // Tambahkan filter berdasarkan tanggal (gunakan updated_at)
        if ($startDate) {
            $query->whereDate('InvoiceDate', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('InvoiceDate', '<=', $endDate);
        }

        // Dapatkan data dengan paginasi
        $transaction = $query->orderBy('InvoiceDate', 'desc')->paginate(10);

        // Return view dengan data
        return view('owner.laporan_penjualan', compact('transaction'));
    }

    public function transaction(Request $request)
    {
        // Ambil data filter dari request
        $customerName = $request->input('customerName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $orderStatus = $request->input('orderStatus');
    
        // Query dasar
        $query = TransactionList::query();
    
        // Tambahkan filter berdasarkan nama pelanggan
        if ($customerName) {
            $query->where('CustomerName', 'LIKE', '%' . $customerName . '%');
        }
    
        // Tambahkan filter berdasarkan tanggal (gunakan InvoiceDate)
        if ($startDate) {
            $query->whereDate('InvoiceDate', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('InvoiceDate', '<=', $endDate);
        }
    
        // Tambahkan filter berdasarkan status pesanan
        if ($orderStatus) {
            $query->where('OrderStatus', $orderStatus);
        }
    
        // Ambil data dengan urutan dan paginasi
        $transaction = $query->orderBy('InvoiceDate', 'desc')->paginate(10);
    
        // Kirim data ke view
        return view('owner.riwayat_transaksi', compact('transaction'));
    }    

}
