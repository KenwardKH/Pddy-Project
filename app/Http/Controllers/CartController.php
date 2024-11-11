<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class CartController extends Controller
{
    /**
     * Menambahkan produk ke keranjang.
     */
    public function addToCart(Request $request)
{
    $cartItems = $request->input('quantity', []);
    $productImages = $request->input('image', []);
    $productPrices = $request->input('price', []);

    $cart = Session::get('cart', []);
    $quantities = Session::get('quantities', []); // Menyimpan kuantitas produk yang dipilih

    foreach ($cartItems as $productName => $quantity) {
        if ($quantity > 0) {
            // Pastikan gambar dan harga ada
            if (!isset($productImages[$productName]) || !isset($productPrices[$productName])) {
                \Log::error("Data tidak lengkap untuk produk: {$productName}");
                continue; // Lewati jika data tidak lengkap
            }

            $image = $productImages[$productName];
            $price = $productPrices[$productName];

            // Simpan item ke keranjang
            $cart[$productName] = [
                'name' => $productName,
                'quantity' => $quantity,
                'image' => $image,
                'price' => $price,
            ];

            // Simpan kuantitas produk dalam sesi
            $quantities[$productName] = $quantity;
        }
    }

    // Simpan keranjang dan kuantitas produk ke dalam sesi
    Session::put('cart', $cart);
    Session::put('quantities', $quantities);

    return redirect()->route('cart.view')->with('success', 'Produk berhasil ditambahkan ke keranjang');
}


    
    public function viewCart()
    {
        // Ambil keranjang dari sesi
        $cart = Session::get('cart', []);
        
        \Log::info('Isi keranjang:', $cart); // Log isi keranjang untuk debugging
    
        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            // Cek apakah 'price' dan 'quantity' ada
            if (!isset($item['price']) || !isset($item['quantity'])) {
                \Log::error("Item tidak memiliki 'price' atau 'quantity':", $item);
                continue; // Lewati item ini jika tidak ada
            }
            $total += $item['price'] * $item['quantity'];
        }
    
        // Tampilkan tampilan keranjang
        return view('pengguna.keranjang', compact('cart', 'total'));
    }
    
    

    /**
     * Menghapus produk dari keranjang.
     */
    public function removeFromCart($productName)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productName])) {
            unset($cart[$productName]); // Hapus produk dari keranjang
            Session::put('cart', $cart);
            return redirect()->route('cart.view')->with('success', 'Produk berhasil dihapus dari keranjang');
        }

        return redirect()->route('cart.view')->with('error', 'Produk tidak ditemukan di keranjang');
    }

    /**
     * Memperbarui jumlah produk dalam keranjang.
     */
    public function updateCart(Request $request)
    {
        $cart = Session::get('cart', []);
        $updatedQuantities = $request->input('quantity', []);

        foreach ($updatedQuantities as $productName => $quantity) {
            if (isset($cart[$productName])) {
                if ($quantity > 0) {
                    $cart[$productName]['quantity'] = $quantity; // Update kuantitas
                } else {
                    unset($cart[$productName]); // Hapus produk jika kuantitas 0
                }
            }
        }

        Session::put('cart', $cart);
        return redirect()->route('cart.view')->with('success', 'Keranjang berhasil diperbarui');
    }
}