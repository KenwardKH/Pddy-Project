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
    // Menampilkan halaman keranjang pengguna
    public function index()
    {
        $userId = Auth::id();

        $customerId = DB::table('customers')
        ->where('user_id', $userId)
        ->value('CustomerID');

        // Mengambil data dari table customer cart
        $cartItems = CustomerCart::with('product')
            ->where('CustomerID', $customerId)
            ->get();

        // Menghitung total harga
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

        // Mengambil quantities dari halaman input
        $quantities = $request->input('quantity', []);

        foreach ($quantities as $productId => $quantity) {
            if ($quantity > 0) {
                // Memastikan memilih produk yang benar dengan ProductID
                $product = Product::find($productId);

                if ($product) {
                    // mencari item di keranjang sesuai dengan customer dan product
                    $cartItem = CustomerCart::where('CustomerID', $customerId)
                                            ->where('ProductID', $productId)
                                            ->first();

                    if ($cartItem) {
                        // Jika produk ada dalam keranjang, perbarui jumlah itemnya
                        $cartItem->Quantity = $quantity;
                        $cartItem->save();
                    } else {
                        // Jika kosong, tambah item baru
                        CustomerCart::create([
                            'CustomerID' => $customerId,
                            'ProductID' => $productId,
                            'Quantity' => $quantity,
                        ]);
                    }
                }
            } else {
                // Jika quantity 0, hapus item dari keranjang
                CustomerCart::where('CustomerID', $customerId)
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
        $customerId = DB::table('customers')
            ->where('user_id', $userId)
            ->value('CustomerID');

        // Hapus item keranjang langsung berdasarkan CustomerID dan ProductID
        CustomerCart::where('CustomerID', $customerId)
            ->where('ProductID', $productId)
            ->delete();

        return redirect()->route('customer.cart')->with('success', 'Barang berhasil dihapus dari keranjang!');
    }
    
    public function getCustomerId()
    {
        // mendapat id user yang login
        $userId = Auth::id();

        // Mengambil CustomerID dari tabel customers berdasarkan user_id
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
        $alamat = $request->input('alamat', null);  // input alamat jika opsi diantar dipilih

        try {
            // memanggil prosedur CheckoutCart
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
