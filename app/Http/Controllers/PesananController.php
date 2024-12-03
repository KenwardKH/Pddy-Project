<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CashierCart;
use App\Models\Kasir;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Menampilkan halaman Buat Pesanan.
     */
    public function buatPesanan(Request $request)
    {
        $userId = Auth::id();

        // Find the Customer associated with this UserID
        $kasir = Kasir::where('User_ID', $userId)->first();
        
        if (!$kasir) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai kasir.');
        }

        $search = $request->input('search');

        $products = Product::with('pricing')
        ->when($search, function ($query, $search) {
            return $query->where('ProductName', 'like', '%' . $search . '%');
        })
        ->get();

        // Retrieve the cart items for the current customer
        $cartItems = CashierCart::where('CashierID', $kasir->id_kasir)
                    ->with('product.pricing') // Include product and pricing details for each cart item
                    ->get()
                    ->keyBy('ProductID'); // Index by ProductID for easier access

        return view('kasir.kasir_buat_pesanan', compact('products', 'cartItems', 'search'));
    }
}
