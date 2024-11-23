<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>informasi_pengguna</title>
    <link rel="stylesheet" href="{{ asset('css/informasi_pengguna.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('pengguna.home') }}">Home</a>
                    <a href="{{ route('profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('pengguna.buat_pesanan') }}">Beli Barang <i class="bi bi-bag-plus"></i></a>
                    <a href="/keranjang">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('pengguna.pembayaran') }}">Daftar Pembayaran <i class="bi bi-cash-stack"></i></a>
                    <a href="{{ route('pengguna.status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('pengguna.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link"
                            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <main>
            <section class="informasi-pribadi">
                <h2>Informasi Pribadi</h2>
                <p>Perbarui informasi profil akun Anda dan alamat email.</p>
                @if (session('info'))
                    <div class="alert alert-success">
                        {{ session('info') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $customer->CustomerName ?? '') }}" required>
                    @error('name')
                        <span></span>
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        required>
                    @error('email')
                        <span></span>
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label for="CustomerContact">Nomor Telepon:</label>
                    <input type="tel" id="CustomerContact" name="CustomerContact"
                        value="{{ old('CustomerContact', $customer->CustomerContact ?? '') }}" required>
                    @error('CustomerContact')
                        <span></span>
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label for="CustomerAddress">Alamat:</label>
                    <input type="text" id="CustomerAddress" name="CustomerAddress"
                        value="{{ old('CustomerAddress', $customer->CustomerAddress ?? '') }}" required>
                    @error('CustomerAddress')
                        <span></span>
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <button type="submit">Simpan</button>
                </form>

            </section>

            <!-- Perbarui Kata Sandi -->
            <section class="perbarui-kata-sandi">
                <h2>Perbarui Kata Sandi</h2>
                <p>Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.</p>
                @if (session('pass'))
                    <div class="alert alert-success">{{ session('pass') }}</div>
                @endif
                <form method="post" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('put')

                    <label for="current_password">Kata Sandi Saat Ini:</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password')
                        <span></span>
                        <span class="error-message">{{ $message }}</span>
                    @enderror


                    <label for="new_password">Kata Sandi Baru:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    @error('new_password')
                        <span></span>
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label for="new_password_confirmation">Konfirmasi Kata Sandi:</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                    @error('new_password_confirmation')
                        <span></span>
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <button type="submit">Simpan</button>
                </form>
            </section>

            <section class="hapus-akun">
                <h2>Hapus Akun</h2>
                <p>Setelah akun Anda dihapus, semua data yang terkait akan dihapus secara permanen. Harap berhati-hati.
                </p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <label for="password">Masukkan Password Anda:</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div></div>
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-danger">Hapus Akun</button>
                </form>
            </section>

        </main>

</body>

</html>
