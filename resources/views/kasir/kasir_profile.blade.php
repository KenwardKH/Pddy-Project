<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kasir Home</title>
        <link rel="stylesheet" href="{{asset('css/kasir_profile.css')}}">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="{{asset('images/logo.png')}}" alt="logo">
                <div class="nav">
                    <div class="left">
                        <a href="home">Home</a>
                        <a href="#">Profil</a>   
                    </div>
                    <div class="right">
                        <a href="buat-pesanan">Buat Pesanan <i class="bi bi-cart"></i></a>
                        <a href="stock-barang">Stock Barang <i class="bi bi-box-seam"></i></a>
                        <a href="konfirmasi">Konfirmasi Pesanan <i class="bi bi-clipboard-check"></i></a>
                        <a href="status">Status Pesanan <i class="bi bi-journal-text"></i></a>
                        <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <main>
                <section class="informasi-pribadi">
                    <h2>Informasi Pribadi</h2>
                    <p>Perbarui informasi profil akun Anda dan alamat email.</p>
                    <form>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name">
        
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email">
        
                        <label for="phone">Nomor Telepon:</label>
                        <input type="tel" id="phone" name="phone">
        
                        <button type="submit">Simpan</button>
                    </form>
                </section>
        
                <section class="perbarui-kata-sandi">
                    <h2>Perbarui Kata Sandi</h2>
                    <p>Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.</p>
                    <form>
                        <label for="current_password">Kata Sandi Saat Ini:</label>
                        <input type="password" id="current_password" name="current_password">
        
                        <label for="new_password">Kata Sandi Baru:</label>
                        <input type="password" id="new_password" name="new_password">
        
                        <label for="confirm_password">Konfirmasi Kata Sandi:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
        
                        <button type="submit">Simpan</button>
                    </form>
                </section>
            </main>
        </div>
</body>
</html>