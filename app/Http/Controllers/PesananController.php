<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CustomerCart;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Menampilkan halaman Buat Pesanan.
     */
    public function buatPesanan()
    {
        $kasir = Auth::user()->kasir; // Mendapatkan data kasir berdasarkan user yang login
        
        if (!$kasir) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai kasir.');
        }

        // Ambil semua produk
        $products = Product::with('pricing')->get();

        // Ambil keranjang pelanggan (dummy pelanggan atau dari session, bisa disesuaikan)
        $customerId = session('customer_id', null); // Ambil customer_id dari session
        $cartItems = CustomerCart::where('CustomerID', $customerId)->get()->keyBy('ProductID');

        return view('kasir.kasir_buat_pesanan', [
            'products' => $products,
            'cartItems' => $cartItems
        ]);
    }
}
