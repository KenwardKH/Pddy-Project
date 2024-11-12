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
        $products = Product::with('pricing')->get();

        return view('pengguna.peralatan_kantor', compact('products'));
    }
}
