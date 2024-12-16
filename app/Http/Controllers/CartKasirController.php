<?php

namespace App\Http\Controllers;
use App\Models\CashierCart;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\DeliveryOrderStatus;
use App\Models\PickupOrderStatus;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartKasirController extends Controller
{
    // Menampilkan keranjang
    public function index()
    {
        $userId = Auth::id();

        $kasirId = DB::table('kasir')
        ->where('user_id', $userId)
        ->value('id_kasir');

        // Mengambil data dari table cart kasir
        $cartItems = CashierCart::with('product')
            ->where('CashierID', $kasirId)
            ->get();

        // hitung total harga
        $total = $cartItems->sum(function ($item) {
            return $item->product->pricing->UnitPrice * $item->Quantity;
        });

        return view('kasir.kasir_keranjang', [
            'cart' => $cartItems,
            'total' => $total,
        ]);
    }

    // Mengupdate keranjang
    public function updateCart(Request $request)
    {
        $userId = Auth::id();

        $kasirId = DB::table('kasir')
        ->where('user_id', $userId)
        ->value('id_kasir');

        // // Mengambil quantities dari halaman input
        $quantities = $request->input('quantity', []);

        foreach ($quantities as $productId => $quantity) {
            if ($quantity > 0) {
                // Ensure the correct product is found by its ProductID
                $product = Product::find($productId);

                if ($product) {
                    // Memastikan memilih produk yang benar dengan ProductID
                    $cartItem = CashierCart::where('CashierID', $kasirId)
                                            ->where('ProductID', $productId)
                                            ->first();

                    if ($cartItem) {
                        // Jika produk ada dalam keranjang, perbarui jumlah itemnya
                        $cartItem->Quantity = $quantity;
                        $cartItem->save();
                    } else {
                        // Jika kosong, tambah item baru
                        CashierCart::create([
                            'CashierID' => $kasirId,
                            'ProductID' => $productId,
                            'Quantity' => $quantity,
                        ]);
                    }
                }
            } else {
                // Jika quantity 0, hapus item dari keranjang
                CashierCart::where('CashierID', $kasirId)
                    ->where('ProductID', $productId)
                    ->delete();
            }
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    // Menghapus item dari keranjang
    public function removeItem($productId)
    {
        $userId = Auth::id();

        // Ambil CustomerID berdasarkan user_id
        $kasirId = DB::table('kasir')
        ->where('user_id', $userId)
        ->value('id_kasir');

        // cari item keranjang sesuai dengan customer dan product untuk dihapus
        CashierCart::where('CashierID', $kasirId)
            ->where('ProductID', $productId)
            ->delete(); 

        return redirect()->route('kasir.cart')->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

    public function checkout(Request $request)
    {
        $customerType = $request->input('customerType'); // Pilihan: 'new' atau 'existing'

        if ($customerType === 'new') {
            $request->validate([
                'newCustomerName' => 'required|string|max:255',
                'newCustomerPhone' => 'required|string|max:15',
            ]);

            $customerId = null; // Pelanggan baru, tidak ada ID
            $customerName = $request->input('newCustomerName');
            $customerPhone = $request->input('newCustomerPhone');
        } elseif ($customerType === 'existing') {
            $request->validate([
                'customerId' => 'required|integer',
                'customerName' => 'required|string|max:255',
                'customerPhone' => 'required|string|max:15',
            ]);

            $customerId = $request->input('customerId'); // Pelanggan terdaftar
            $customerName = $request->input('customerName');
            $customerPhone = $request->input('customerPhone');
        } else {
            return redirect()->route('buat-pesanan');
        }
        
        // mendapat id kasir yang login
        $userId = Auth::id();
        $kasir = DB::table('kasir')
            ->where('user_id', $userId)
            ->select('id_kasir')
            ->first();

        if (!$kasir) {
            return response()->json(['error' => 'Cashier not found'], 404);
        }

        $cashierId = $kasir->id_kasir;
        $shippingOption = $request->input('shipping_option');
        $paymentOption = $request->input('payment_option');
        $alamat = $request->input('alamat', null); 
        $total = $request->input('total'); // Ambil total dari hidden input

        try {
            // panggil procedure
            DB::statement('CALL CheckoutCashierCart(?, ?, ?, ?, ?, ?, ?)', [
                $cashierId,
                $customerId,
                $customerName,
                $customerPhone,
                $shippingOption,
                $paymentOption,
                $alamat
            ]);

             // mengambil invoiceid dari invoice terbaru
            $invoiceId = DB::table('invoices')->latest('InvoiceID')->value('InvoiceID'); 

            // Masukkan data payment
            $payment = Payment::create([
                'InvoiceID' => $invoiceId,
                'PaymentDate' => now(),
                'AmountPaid' => $total, // Gunakan total pesanan sebagai jumlah dibayar
                'PaymentImage' => null // Jika bukti pembayaran tidak diperlukan
            ]);

            return redirect()->route('kasir.cart')->with('success', 'Pesanan berhasil dibuat.');;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
