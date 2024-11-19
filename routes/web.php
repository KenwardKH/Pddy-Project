<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\InformasiPenggunaController;
use App\Http\Controllers\PeralatanKantorController;
use App\Http\Controllers\PeralatanSekolahController;
use App\Http\Controllers\BukuDanKertasController;
use App\Http\Controllers\PulpenDanPensilController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);


//pengguna
Route::get('/pengguna/home', [PenggunaController::class, 'home'])->name('pengguna.home');
Route::get('/pengguna/profile', [InformasiPenggunaController::class, 'show'])->name('profile.show');
Route::get('/pengguna/status', [PenggunaController::class, 'status'])->name('pengguna.status');
Route::get('/pengguna/riwayat', [PenggunaController::class, 'riwayat'])->name('pengguna.riwayat');
Route::get('/pengguna/buat_pesanan', [PeralatanKantorController::class, 'index'])->name('pengguna.buat_pesanan');
Route::get('/addToOrder', [PeralatanKantorController::class, 'addToOrder'])->name('pengguna.addToOrder');

//profile pengguna
Route::patch('/pengguna/profile', [InformasiPenggunaController::class, 'update'])->name('profile.update');
Route::put('/pengguna/profile/password', [InformasiPenggunaController::class, 'updatePassword'])->name('profile.password.update');
Route::delete('pengguna/profile', [InformasiPenggunaController::class, 'destroy'])->name('profile.destroy');

//keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('customer.cart');
Route::post('/keranjang/update', [CartController::class, 'updateCart'])->name('customer.updateCart');
Route::get('/keranjang/remove/{productName}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/api/invoice/{id}', [PenggunaController::class, 'getInvoiceDetails']);


//Kasir
Route::get('/kasir/home', [KasirController::class, 'home'])->name('kasir.home');
Route::get('/kasir/buat-pesanan', [PesananController::class, 'buatPesanan'])->name('buat-pesanan');
Route::post('/kasir/tambah-pesanan', [PesananController::class, 'addToOrder'])->name('tambah-pesanan');
Route::get('/kasir/stock-barang', [KasirController::class, 'stock'])->name('kasir.stock');
Route::get('/kasir/konfirmasi', [KasirController::class, 'konfirmasi'])->name('kasir.konfirmasi');
Route::get('/kasir/status/{type?}', [KasirController::class, 'index'])->name('status');
Route::get('/kasir/status/update/{id}/{type}', [KasirController::class, 'updateStatus'])->name('status.update');

//owner
Route::get('/owner/home', [OwnerController::class, 'index'])->name('owner.home');
Route::get('/owner/user', [OwnerController::class, 'user'])->name('owner.user');
Route::get('/owner/daftar-pembeli', [OwnerController::class, 'customer'])->name('owner.daftar-costumer');
Route::get('/owner/daftar-kasir', [OwnerController::class, 'kasir'])->name('owner.daftar-kasir');
Route::post('/tambah-kasir', [OwnerController::class, 'tambahKasir'])->name('owner.add-kasir');
Route::get('/owner/daftar-supplier', [OwnerController::class, 'supplier'])->name('owner.daftar-supplier');
Route::post('/add-supplier', [OwnerController::class, 'addSupplier'])->name('owner.add-supplier');
Route::get('/owner/log-transaksi', [OwnerController::class, 'transaksi'])->name('owner.log-transaksi');

require __DIR__.'/auth.php';