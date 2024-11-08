<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('welcome');
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

//Kasir
Route::get('/kasir/home', [KasirController::class, 'home'])->name('kasir.home');
Route::get('/kasir/buat-pesanan', [PesananController::class, 'index'])->name('buat-pesanan');
Route::post('/kasir/tambah-pesanan', [PesananController::class, 'addToOrder'])->name('tambah-pesanan');


require __DIR__.'/auth.php';
