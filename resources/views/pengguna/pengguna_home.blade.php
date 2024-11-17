<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - Sinar Pelangi</title>
    <link rel="stylesheet" href="{{ asset('css/pengguna_home.css') }}">
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
                    <a href="{{ route('pengguna.status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('pengguna.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a> 
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Hero Section -->
        <div class="hero-section">
            <img class="hero-image" src="{{ asset('images/hero-background.png') }}" alt="Background Image">
        </div>

        <!-- Categories Section -->
        <div class="categories">
            <div class="category">
                <a href="{{ route('pengguna.buat_pesanan') }}">
                    <button>Mulai Berbelanja</button>
                </a>
            </div>
        </div>
    </div>


    <!-- Contact Section -->
    <div class="contact">
        <p>Telepon: +62 811 1010</p>
    </div>
    </div>


</body>

</html>
