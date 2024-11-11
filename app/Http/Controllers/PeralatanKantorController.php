<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\pengguna;

use Illuminate\Http\Request;
use Session;
use App\Models\Product;


class PeralatanKantorController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'pricing'])
        ->whereHas('category', function ($query) {
            $query->where('CategoryName', 'Peralatan Kantor');
        })
        ->get();

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
