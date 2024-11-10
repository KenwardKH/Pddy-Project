<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\pengguna;

use Illuminate\Http\Request;
use Session;

class PeralatanKantorController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'name' => 'Pensil Ajaib 2B', 'price' => 25000, 'available' => 60, 'image' => 'pensil2b.png'],
            ['id' => 2, 'name' => 'Penghapus Ajaib', 'price' => 15000, 'available' => 70, 'image' => 'eraser.png']
        ];
        return view('pengguna.peralatan_kantor', compact('products'));
    }

    public function addToOrder(Request $request)
    {
        // Retrieve quantities from the form input
        $quantities = $request->input('quantity');

        // Initialize or retrieve the current order from the session
        $order = Session::get('order', []);

        foreach ($quantities as $productName => $quantity) {
            if ($quantity > 0) {
                // Check if the product is already in the order
                if (isset($order[$productName])) {
                    // Update the quantity if it already exists
                    $order[$productName]['quantity'] += $quantity;
                } else {
                    // Add new product to the order
                    $order[$productName] = [
                        'name' => $productName,
                        'quantity' => $quantity
                    ];
                }
            }
        }
        
        Session::put('order', $order);

        return redirect()->route('pengguna.peralatan_kantor')->with('success', 'Produk berhasil ditambahkan ke pesanan!');
    }
}
