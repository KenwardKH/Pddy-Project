<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerCart;
use App\Models\Customer;
use App\Models\Pricing;
// namespace App\Http\Controllers\pengguna;

use Illuminate\Http\Request;
use Session;
use App\Models\Product;


class PeralatanKantorController extends Controller
{

    public function index(Request $request)
    {
        // Get the UserID of the currently authenticated user
        $userId = Auth::id();

        // Find the Customer associated with this UserID
        $customer = Customer::where('User_ID', $userId)->first();

        // Check if the customer exists
        if (!$customer) {
            // Handle the case if there’s no matching customer, e.g., redirect or return an error message
            return redirect()->route('some.route')->with('error', 'Customer not found.');
        }

        // Retrieve all products with their pricing
        $query = Product::with('pricing');

        // If there's a search query, filter the products by name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('ProductName', 'like', '%' . $request->search . '%');
        }

        // Get the filtered products
        $products = $query->get();

        // Retrieve the cart items for the current customer
        $cartItems = CustomerCart::where('CustomerID', $customer->CustomerID)
                    ->with('product.pricing') // Include product and pricing details for each cart item
                    ->get()
                    ->keyBy('ProductID'); // Index by ProductID for easier access

        // Pass both products and cartItems to the view
        return view('pengguna.peralatan_kantor', compact('products', 'cartItems'));
    }

}
