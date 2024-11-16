<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kasir;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        // Mengambil jumlah produk, kasir, dan pelanggan
        $produkCount = Product::count();
        $kasirCount = Kasir::count();
        $pembeliCount = Customer::count();
        $suplierCount = Supplier::count();  // Anda harus membuat model Supplier terlebih dahulu jika belum ada

        // Mengambil produk populer (misalnya berdasarkan jumlah stok)
        $produkPopuler = Product::orderBy('CurrentStock', 'desc')->take(5)->get();

        // Laporan penjualan (misalnya dalam 1 minggu terakhir)
        // $laporanPenjualan = Product::where('created_at', '>=', now()->subWeek())->get();

        // Mengirim data ke view
        return view('owner.dashboard', compact(
            'produkCount', 
            'kasirCount', 
            'pembeliCount', 
            'suplierCount', 
            'produkPopuler',
            // 'laporanPenjualan'
        ));
    }

    public function user()
    {
        return view('owner.user');
    }

    public function customer()
    {
        // Mengambil semua data customer
        $customers = Customer::all();

        // Mengirim data customers ke view
        return view('owner.daftar_pembeli', compact('customers'));
    }

    public function kasir()
    {
        // Mengambil semua data customer
        $kasirs = Kasir::all();

        // Mengirim data customers ke view
        return view('owner.daftar_kasir', compact('kasirs'));
    }

    public function supplier()
    {
        // Mengambil semua data customer
        $suppliers = Supplier::all();

        // Mengirim data customers ke view
        return view('owner.supplier', compact('suppliers'));
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
        ]);
        
        // Membuat user baru
        $user = User::create([
            'email' => $validated['email'],
            'password' => bcrypt('password123'),  // Password default, bisa diganti
        ]);
        
        // Simpan data kasir ke database
        Kasir::create([
            'user_id' => $user->id,  // Mengaitkan kasir dengan user
            'nama_kasir' => $validated['nama_kasir'],
            'kontak_kasir' => $validated['kontak_kasir'],
            'alamat_kasir' => $validated['alamat_kasir'],
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('owner.daftar-kasir')->with('success', 'Kasir berhasil ditambahkan.');
    }


}
