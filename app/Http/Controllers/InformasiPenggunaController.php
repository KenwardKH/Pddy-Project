<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class InformasiPenggunaController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $customer = $user->customer; // Mengambil data customer terkait

        return view('pengguna.profile', compact('user', 'customer'));
    }

    public function showKasir()
    {
        $user = Auth::user();
        $kasir = $user->kasir; // Mengambil data kasir terkait

        return view('kasir.kasir_profile', compact('user', 'kasir'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'CustomerContact' => 'required|digits_between:10,15',
            'CustomerAddress' => 'required|string|max:255',
        ]);

        // Update data user
        $user->update([
            'email' => $request->email,
        ]);

        // Update data customer
        $customer = $user->customer;
        if ($customer) {
            $customer->update([
                'CustomerName' => $request->name,
                'CustomerContact' => $request->CustomerContact,
                'CustomerAddress' => $request->CustomerAddress,
            ]);
        } else {
            Customer::create([
                'user_id' => $user->id,
                'CustomerName' => $request->name,
                'CustomerContact' => $request->CustomerContact,
                'CustomerAddress' => $request->CustomerAddress,
            ]);
        }

        return redirect()->route('profile.show')->with('info', 'Informasi diri Anda berhasil diperbarui.');
    }

    public function updateKasir(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'KasirContact' => 'required|digits_between:10,15',
            'KasirAddress' => 'required|string|max:255',
        ]);

        // Update data user
        $user->update([
            'email' => $request->email,
        ]);

        // Update data customer
        $kasir = $user->kasir;
        if ($kasir) {
            $kasir->update([
                'nama_kasir' => $request->name,
                'kontak_kasir' => $request->KasirContact,
                'alamat_kasir' => $request->KasirAddress,
            ]);
        } else {
            Kasir::create([
                'user_id' => $user->id,
                'nama_kasir' => $request->name,
                'kontak_kasir' => $request->KasirContact,
                'alamat_kasir' => $request->KasirAddress,
            ]);
        }

        return redirect()->route('kasir.profile.show')->with('info', 'Informasi diri Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password saat ini benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('pass', 'Password berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Validasi password sebelum menghapus akun
        $request->validate([
            'password' => 'required',
        ]);

        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah.']);
        }

        // Hapus relasi terkait
        $user->customer()->delete(); // Hapus data di tabel customers
        $user->kasir()->delete();    // Hapus data di tabel kasir 

        // Hapus akun pengguna
        $user->delete();

        // Logout pengguna
        Auth::logout();

        // Redirect ke halaman utama dengan pesan
        return redirect('/')->with('status', 'Akun Anda telah berhasil dihapus.');
    }

}
