<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Ambil data produk dari form
        $cartItems = $request->input('quantity');
        
        $cart = Session::get('cart', []);

        // Tambahkan item ke dalam session keranjang
        foreach ($cartItems as $productName => $quantity) {
            if ($quantity > 0) {
                $cart[$productName] = [
                    'name' => $productName,
                    'quantity' => $quantity,
                ];
            }
        }

        // Simpan keranjang ke session
        Session::put('cart', $cart);

        // Redirect ke halaman keranjang
        return redirect()->route('cart.view')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function viewCart()
    {
        $cart = Session::get('cart', []);
        return view('pengguna.keranjang', compact('cart'));
    }
//     public function showKeranjang()
// {
//     return view('keranjang');
// }
}
