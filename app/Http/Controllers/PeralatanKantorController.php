<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerCart;
use App\Models\Customer;
use App\Models\Pricing;

use Illuminate\Http\Request;
use Session;
use App\Models\Product;


class PeralatanKantorController extends Controller
{

    public function index(Request $request)
    {
        // mendapatkan user id dari user yang login
        $userId = Auth::id();

        // mencari customer berdasarkan userid
        $customer = Customer::where('User_ID', $userId)->first();

        // memeriksa apakah customer ada
        if (!$customer) {
            // Handle the case if there’s no matching customer, e.g., redirect or return an error message
            return redirect()->route('some.route')->with('error', 'Customer not found.');
        }

        $query = Product::with('pricing');

        // filter berdasarkan nama produk
        if ($request->has('search') && !empty($request->search)) {
            $query->where('ProductName', 'like', '%' . $request->search . '%');
        }

        // tampilkan produk yang difilter
        $products = $query->get();

        // tampilkan cart item untuk customer
        $cartItems = CustomerCart::where('CustomerID', $customer->CustomerID)
                    ->with('product.pricing')
                    ->get()
                    ->keyBy('ProductID');

        return view('pengguna.peralatan_kantor', compact('products', 'cartItems'));
    }

}
