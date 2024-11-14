<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransactionLog;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
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
        // Ambil invoice dengan status belum selesai
        $invoices = Invoice::with(['invoiceDetails', 'transactionLog', 'deliveryStatus', 'pickupStatus'])
            ->whereHas('deliveryStatus', function ($query) {
                $query->where('status', '!=', 'Selesai');
            })
            ->orWhereHas('pickupStatus', function ($query) {
                $query->where('status', '!=', 'Selesai');
            })
            ->orderBy('InvoiceID', 'asc')
            ->get();

        return view('pengguna.pengguna_status', compact('invoices'));
    }

    public function riwayat()
    {
        // Ambil invoice dengan status selesai
        $invoices = Invoice::with(['invoiceDetails', 'transactionLog', 'deliveryStatus', 'pickupStatus'])
            ->whereHas('deliveryStatus', function ($query) {
                $query->where('status', 'Selesai');
            })
            ->orWhereHas('pickupStatus', function ($query) {
                $query->where('status', 'Selesai');
            })
            ->orderBy('InvoiceID', 'asc')
            ->get();

        return view('pengguna.pengguna_riwayat', compact('invoices'));
    }


}
