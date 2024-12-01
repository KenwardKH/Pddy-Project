<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionList;

class TransactionController extends Controller
{
    public function index()
    {
        // Retrieve all data from the transaction_list view
        $transaction = TransactionList::where('OrderStatus', 'selesai')
            ->orderBy('InvoiceDate', 'desc')
            ->get();

        // Pass the data to the view
        return view('owner.laporan_penjualan', compact('transaction'));
    }

    public function searchCustomer(Request $request)
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
        $transaction = $query->orderBy('InvoiceDate', 'desc')->get();

        // Return view dengan data
        return view('owner.laporan_penjualan', compact('transaction'));
    }

    public function transaction()
    {
        // Retrieve and order data by InvoiceDate in descending order
        $transaction = TransactionList::orderBy('InvoiceDate', 'desc')->get();

        // Pass the data to the view
        return view('owner.riwayat_transaksi', compact('transaction'));
    }


    public function filterTransaksi(Request $request)
    {
        // Ambil data filter
        $customerName = $request->input('customerName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $orderStatus = $request->input('orderStatus'); // Get the order status filter

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

        // Dapatkan data dengan paginasi
        $transaction = $query->orderBy('InvoiceDate', 'desc')
            ->get();

        // Return view dengan data
        return view('owner.riwayat_transaksi', compact('transaction'));
    }


}
