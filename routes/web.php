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
use App\Http\Controllers\CartKasirController;
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
Route::get('/pengguna/pembayaran', [PenggunaController::class, 'pembayaran'])->name('pengguna.pembayaran');
Route::get('/pengguna/status', [PenggunaController::class, 'status'])->name('pengguna.status');
Route::get('/pengguna/riwayat', [PenggunaController::class, 'riwayat'])->name('pengguna.riwayat');
Route::get('/pengguna/riwayat/batal', [PenggunaController::class, 'riwayatBatal'])->name('pengguna.riwayat.batal');
Route::get('/pengguna/buat_pesanan', [PeralatanKantorController::class, 'index'])->name('pengguna.buat_pesanan');
Route::get('/addToOrder', [PeralatanKantorController::class, 'addToOrder'])->name('pengguna.addToOrder');
Route::post('/pembayaran/upload', [PenggunaController::class, 'bukti_transfer'])->name('pembayaran.upload');
Route::post('/pengguna/batal/{id}', [PenggunaController::class, 'batal'])->name('pengguna.batal');

//profile pengguna
Route::get('/pengguna/profile', [InformasiPenggunaController::class, 'show'])->name('profile.show');
Route::patch('/pengguna/profile', [InformasiPenggunaController::class, 'update'])->name('profile.update');
Route::put('/pengguna/profile/password', [InformasiPenggunaController::class, 'updatePassword'])->name('profile.password.update');
Route::delete('pengguna/profile', [InformasiPenggunaController::class, 'destroy'])->name('profile.destroy');
Route::post('/pengguna/{id}/batal', [PenggunaController::class, 'batal'])->name('pengguna.batal');

//keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('customer.cart');
Route::post('/keranjang/update', [CartController::class, 'updateCart'])->name('customer.updateCart');
Route::get('/keranjang/remove/{productName}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/api/invoice/{id}', [PenggunaController::class, 'getInvoiceDetails']);


//Kasir
Route::get('/kasir/home', [KasirController::class, 'home'])->name('kasir.home');
Route::get('/kasir/pembayaran', [KasirController::class, 'pembayaran'])->name('kasir.pembayaran');
Route::post('/kasir/{id}/konfirmasi', [KasirController::class, 'konfirmasi'])->name('kasir.konfirmasi');
Route::get('/kasir/buat-pesanan', [PesananController::class, 'buatPesanan'])->name('buat-pesanan');
Route::get('/kasir/stock-barang', [KasirController::class, 'stock'])->name('kasir.stock');
Route::get('/kasir/status/{type?}', [KasirController::class, 'status'])->name('status');
Route::delete('/hapus/pesanan/{id}', [KasirController::class, 'destroy'])->name('pesanan.destroy');
Route::post('/status/next/{id}', [KasirController::class, 'nextStatus'])->name('status.next');
Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
Route::get('/kasir/riwayat/batal', [KasirController::class, 'riwayatBatal'])->name('kasir.riwayat.batal');
Route::post('/kasir/batal/{id}', [KasirController::class, 'batal'])->name('kasir.batal');

//profile kasir
Route::get('/kasir/profile', [InformasiPenggunaController::class, 'showKasir'])->name('kasir.profile.show');
Route::patch('/kasir/profile', [InformasiPenggunaController::class, 'updateKasir'])->name('kasir.profile.update');
Route::put('/kasir/profile/password', [InformasiPenggunaController::class, 'updatePassword'])->name('kasir.profile.password.update');
Route::delete('kasir/profile', [InformasiPenggunaController::class, 'destroy'])->name('kasir.profile.destroy');

//Kasir keranjang
Route::get('/kasir/keranjang', [CartKasirController::class, 'index'])->name('kasir.cart');
Route::post('/kasir/keranjang-update', [CartKasirController::class, 'updateCart'])->name('kasir.updateCart');
Route::get('/keranjang/hapus/{productName}', [CartKasirController::class, 'removeItem'])->name('kasir.cart.remove');
Route::post('/kasir/checkout', [CartKasirController::class, 'checkout'])->name('kasir.checkout');

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