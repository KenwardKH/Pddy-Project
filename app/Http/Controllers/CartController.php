<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerCart;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan keranjang
    public function index()
    {
        $customerId = Auth::id();

        // Get cart items for the customer with associated product data
        $cartItems = CustomerCart::with('product')
            ->where('CustomerID', $customerId)
            ->get();

        // Calculate total price
        $total = $cartItems->sum(function ($item) {
            return $item->product->pricing->UnitPrice * $item->Quantity;
        });

        return view('pengguna.keranjang', [
            'cart' => $cartItems,
            'total' => $total,
        ]);
    }


    // Menambahkan produk ke keranjang
    public function addToCart(Request $request)
    {
        $customerId = auth()->user()->customer->CustomerID;
        $quantities = $request->input('quantity');

        foreach ($quantities as $productId => $quantity) {
            if ($quantity > 0) {
                // Ensure the correct product is found by its ProductID
                $product = Product::find($productId);

                if ($product) {
                    // Check if the product already exists in the cart
                    $existingCartItem = CustomerCart::where('CustomerID', $customerId)
                        ->where('ProductID', $productId)
                        ->first();

                    if ($existingCartItem) {
                        // If product exists, update quantity
                        $existingCartItem->Quantity += $quantity;
                        $existingCartItem->save();
                    } else {
                        // If product doesn't exist, create a new cart item
                        CustomerCart::create([
                            'CustomerID' => $customerId,
                            'ProductID' => $productId,
                            'Quantity' => $quantity,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('customer.cart');
    }


    
    // Menghapus item dari keranjang
    public function removeItem($productName)
    {
        $customerId = Auth::id();

        // Find the product by its name
        $product = Product::where('ProductName', $productName)->first();

        if ($product) {
            // Find the cart item associated with the customer and product
            CustomerCart::where('CustomerID', $customerId)
                ->where('ProductID', $product->ProductID)
                ->delete(); // Delete the cart item
        }

        return redirect()->route('customer.cart')->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

}
