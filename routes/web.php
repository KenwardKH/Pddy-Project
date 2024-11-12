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
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);


//pengguna
Route::get('/pengguna/home', [PenggunaController::class, 'home'])->name('pengguna.home');
Route::get('/pengguna/profile', [InformasiPenggunaController::class, 'show'])->name('profile.show');
Route::get('/kategori/peralatan_kantor', [PeralatanKantorController::class, 'index'])->name('pengguna.peralatan_kantor.index');
Route::get('/addToOrder', [PeralatanKantorController::class, 'addToOrder'])->name('pengguna.addToOrder');
//keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('customer.cart');
Route::post('/keranjang/add', [CartController::class, 'addToCart'])->name('customer.addToCart');
Route::get('/keranjang/remove/{productName}', [CartController::class, 'removeItem'])->name('cart.remove');

//Kasir
Route::get('/kasir/home', [KasirController::class, 'home'])->name('kasir.home');
Route::get('/kasir/buat-pesanan', [PesananController::class, 'index'])->name('buat-pesanan');
Route::post('/kasir/tambah-pesanan', [PesananController::class, 'addToOrder'])->name('tambah-pesanan');
Route::get('/kasir/stock-barang', [KasirController::class, 'stock'])->name('kasir.stock');
Route::get('/kasir/konfirmasi', [KasirController::class, 'konfirmasi'])->name('kasir.konfirmasi');
Route::get('/kasir/status', [KasirController::class, 'status'])->name('kasir.status');

require __DIR__.'/auth.php';