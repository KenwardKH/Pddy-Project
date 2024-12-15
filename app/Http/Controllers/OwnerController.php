<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Kasir;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use App\Models\OrderStatusLog;
use DB;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        // Mengambil jumlah produk, kasir, pelanggan, dan supplier
        $produkCount = Product::count();
        $kasirCount = Kasir::count();
        $pembeliCount = Customer::count();
        $suplierCount = Supplier::count();

        // Mengambil produk populer (misalnya berdasarkan jumlah stok)
        $produkPopuler = Product::orderBy('CurrentStock', 'desc')->take(5)->get();
        $reports = DB::table('financial_report')
            ->select('ReportMonth', 'TotalTransactions')
            ->orderBy('ReportMonth', 'asc')
            ->get();
        $popular_products = DB::table('popular_products')
            ->select('month', 'productName','sold')
            ->orderBy('month', 'asc')
            ->get();
        // Laporan penjualan (misalnya dalam 1 minggu terakhir)
        // $laporanPenjualan = Product::where('created_at', '>=', now()->subWeek())->get();

        // Mengirim data ke view
        return view('owner.dashboard', compact(
            'produkCount', 
            'kasirCount', 
            'pembeliCount', 
            'suplierCount', 
            'produkPopuler',
            'reports',
            'popular_products'
        ));
    }

    public function user()
    {
        return view('owner.user');
    }

    public function customer()
    {
        // Mengambil semua data customer
        $customers = Customer::with('user')->orderBy('CustomerName','asc')
        ->get();

        // Mengirim data customers ke view
        return view('owner.daftar_pembeli', compact('customers'));
    }

    public function kasir()
    {
        // Mengambil semua data customer
        $kasirs = Kasir::orderBy('nama_kasir','asc')
        ->get();

        // Mengirim data customers ke view
        return view('owner.daftar_kasir', compact('kasirs'));
    }

    public function addSupplier(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'SupplierName' => 'required|string|max:255',
            'SupplierContact' => 'required|string|max:20',
            'SupplierAddress' => 'required|string|max:255',
        ]);
    
        // Simpan data supplier ke database
        Supplier::create($validated);
    
        // Redirect dengan pesan sukses
        return redirect()->route('owner.daftar-supplier')->with('success', 'Supplier berhasil ditambahkan.');
    }
    
    public function tambahKasir(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kasir' => 'required|string|max:255',
            'kontak_kasir' => 'required|string|max:20',
            'alamat_kasir' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',  // Validasi email untuk user baru
            'password' => 'required|string|confirmed|min:8',
        ]);
        
        // Membuat user baru
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($request->password), 
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);
        
        // Simpan data kasir ke database
        Kasir::create([
            'user_id' => $user->id,  // Mengaitkan kasir dengan user
            'nama_kasir' => $validated['nama_kasir'],
            'alamat_kasir' => $validated['alamat_kasir'],
            'kontak_kasir' => $validated['kontak_kasir'],
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('owner.daftar-kasir')->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function updateKasir(Request $request, $id)
    {
        // Cari data kasir
        $kasir = Kasir::findOrFail($id);
        $user = $kasir->user;

        // Validasi input
        $validated = $request->validate([
            'nama_kasir' => 'required|string|max:255',
            'kontak_kasir' => 'required|string|max:20',
            'alamat_kasir' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id', // Abaikan email user ini
        ]);

        // Update user dan kasir
        $user->update(['email' => $validated['email']]);
        $kasir->update([
            'nama_kasir' => $validated['nama_kasir'],
            'alamat_kasir' => $validated['alamat_kasir'],
            'kontak_kasir' => $validated['kontak_kasir'],
        ]);

        return redirect()->route('owner.daftar-kasir')->with('success', 'Kasir berhasil diperbarui.');
    }



    public function destroyCustomer($id)
    {
        // Cari data pelanggan berdasarkan ID
        $customer = Customer::findOrFail($id);

        // Hapus data pelanggan
        $customer->delete();
        $customer->user()->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('owner.daftar-costumer')->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function destroyKasir($id)
    {
        // Cari data kasir berdasarkan ID
        $kasir = Kasir::findOrFail($id);

        // Hapus data kasir
        $kasir->delete();
        $kasir->user()->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('owner.daftar-kasir')->with('success', 'Kasir berhasil dihapus.');
    }

    public function aktivitasKasir(Request $request)
    {
        // Ambil data filter
        $cashierName = $request->input('cashierName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
    
        // Query dasar
        $query = OrderStatusLog::query()->whereNotNull('cashier_name');
    
        // Tambahkan filter berdasarkan nama kasir
        if ($cashierName) {
            $query->where('cashier_name', 'LIKE', '%' . $cashierName . '%');
        }
    
        // Tambahkan filter berdasarkan tanggal (gunakan updated_at)
        if ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }
    
        // Dapatkan data dengan paginasi
        $logs = $query->orderBy('updated_at', 'desc')->paginate();
    
        // Return view dengan data
        return view('owner.history_kasir', compact('logs'));
    }
}
