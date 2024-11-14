<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;


class PenggunaController extends Controller
{
    public function home()
    {
        return view('pengguna.pengguna_home');
    }

    public function status()
    {
        // Assuming you want to get invoices for a specific customer
        // You might get the customer ID from an authenticated user, or pass it as a parameter
        $userId = Auth::id();
        $customerId = DB::table('customers')
            ->where('user_id', $userId)
            ->select('CustomerID');

        $invoices = Invoice::where('CustomerID', $customerId)
            ->with(['deliveryStatus', 'pickupStatus','transactionLog']) // Eager load statuses
            ->get();

        return view('pengguna.pengguna_status', compact('invoices'));
    }

    public function riwayat()
    {
        return view('pengguna.pengguna_riwayat');
    }
}
