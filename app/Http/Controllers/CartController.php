<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerCart;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    // Menampilkan keranjang
    public function index()
    {
        $userId = Auth::id();

        $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

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

    // Mengupdate keranjang
    public function updateCart(Request $request)
    {
        $userId = Auth::id();

        $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

        // Get quantities from the form input
        $quantities = $request->input('quantity', []);

        foreach ($quantities as $productId => $quantity) {
            if ($quantity > 0) {
                // Ensure the correct product is found by its ProductID
                $product = Product::find($productId);

                if ($product) {
                    // Find the cart item associated with the customer and product
                    $cartItem = CustomerCart::where('CustomerID', $customerId)
                                            ->where('ProductID', $productId)
                                            ->first();

                    if ($cartItem) {
                        // Update the quantity if the product exists in the cart
                        $cartItem->Quantity = $quantity;
                        $cartItem->save();
                    } else {
                        // If product is not in the cart, create a new cart item
                        CustomerCart::create([
                            'CustomerID' => $customerId,
                            'ProductID' => $productId,
                            'Quantity' => $quantity,
                        ]);
                    }
                }
            } else {
                // If quantity is 0, remove the item from the cart
                CustomerCart::where('CustomerID', $customerId)
                    ->where('ProductID', $productId)
                    ->delete();
            }
        }

        return redirect()->route('customer.cart')->with('success', 'Keranjang berhasil diperbarui!');
    }

    // Menghapus item dari keranjang
    public function removeItem($productName)
    {
        $userId = Auth::id();

        // Ambil CustomerID berdasarkan user_id
        $customerId = DB::table('customers')
            ->where('user_id', $userId)
            ->value('CustomerID');

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
    
    public function getCustomerId()
    {
        // Get the authenticated user's id
        $userId = Auth::id();

        // Fetch the CustomerID from the customers table
        $customer = DB::table('customers')
            ->where('user_id', $userId)
            ->select('CustomerID')
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Return the CustomerID
        return $customer->CustomerID;
    }
    public function checkout(Request $request)
{
    // Get the authenticated user's CustomerID
    $userId = Auth::id();
    $customer = DB::table('customers')
        ->where('user_id', $userId)
        ->select('CustomerID')
        ->first();

    if (!$customer) {
        return response()->json(['error' => 'Customer not found'], 404);
    }

    $customerId = $customer->CustomerID;
    $shippingOption = $request->input('shipping_option');
    $paymentOption = 'transfer';
    $alamat = $request->input('alamat', null);  // Address if delivery option selected

    try {
        // Call the stored procedure with additional parameters
        DB::statement('CALL CheckoutCart(?, ?, ?, ?)', [
            $customerId,
            $shippingOption,
            $paymentOption,
            $alamat
        ]);

        return redirect()->route('pengguna.pembayaran');
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
