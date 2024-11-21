<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('kasir.home') }}">Home</a>
                    <a href="#">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('buat-pesanan') }}">Buat Pesanan <i class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('kasir.cart') }}">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('kasir.stock') }}">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="{{ route('status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="#">Riwayat Pesanan <i class="bi bi-journal-text"></i></a>
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

        <div class="dashboard">
            <a href="#" class="card card-green">
                <div><b>Pesanan Online</b></div>
                <div class="count">{{ $data['onlineOrders'] }}</div>
                <div><i class="bi bi-arrow-right-square" style="font-size:30px;"></i></div>
            </a>
            <a href="#" class="card card-yellow">
                <div><b>Belum Dibayar</b></div>
                <div class="count">{{ $data['unpaidOrders'] }}</div>
                <div><i class="bi bi-arrow-right-square" style="font-size:30px;"></i></div>
            </a>
            <a href="#" class="card card-blue">
                <div><b>Sedang Diproses</b></div>
                <div class="count">{{ $data['processingOrders'] }}</div>
                <div><i class="bi bi-arrow-right-square" style="font-size:30px;"></i></div>
            </a>
            <a href="#" class="card card-green">
                <div><b>Buat Pesanan</b></div>
                <div><i class="bi bi-cart" style="font-size:40px;"></i></div>
            </a>
            <a href="#" class="card card-red">
                <div><b>Stock Hampir Habis</b></div>
                <div class="count">{{ $data['stockRunningLow'] }}</div>
                <div><i class="bi bi-arrow-right-square" style="font-size:30px;"></i></div>
            </a>
            <a href="#" class="card card-gray">
                <div><b>Status Pesanan</b></div>
                <div><i class="bi bi-journal-text" style="font-size:40px;"></i></div>
            </a>
        </div>
    </div>

</body>

</html>
