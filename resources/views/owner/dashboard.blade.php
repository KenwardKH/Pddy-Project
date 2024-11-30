<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_owner.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('owner.home') }}">Home</a>
                </div>
                <div class="right">
                    <a href="{{ route('owner.product') }}">Produk <i class="bi bi-box-seam"></i></a>
                    <a href="{{ route('owner.daftar-supplier') }}">Supplier<i class="bi bi-shop"></i></a>
                    <a href="{{ route('owner.daftarSupply') }}">Riwayat Pembelian Supply <i class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('owner.daftar-costumer') }}">User<i class="bi bi-person"></i></a>
                    <a href="{{ route('owner.log-transaksi') }}">Riwayat Transaksi <i
                            class="bi bi-receipt-cutoff"></i></a>
                    <a href="#">Laporan Keuangan<i class="bi bi-journal-text"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-link"
                            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="item">
                <button class="btn">
                    <i class="bi bi-box-seam"></i>
                    <span>Produk<br>{{ $produkCount }}</span>
                </button>
            </div>
            <div class="item">
                <button class="btn">
                    <i class="bi bi-person"></i>
                    <span>Kasir<br>{{ $kasirCount }}</span>
                </button>
            </div>
            <div class="item">
                <button class="btn">
                    <i class="bi bi-person"></i>
                    <span>Pembeli<br>{{ $pembeliCount }}</span>
                </button>
            </div>
            <div class="item">
                <button class="btn">
                    <i class="bi bi-shop"></i>
                    <span>Suplier<br>{{ $suplierCount }}</span>
                </button>
            </div>
        </div>

        <!-- Produk Populer -->
        <div class="produk-populer">
            <h2>Produk Populer</h2>
            <div class="produk-list">
                    <div class="produk-item">
                        <p></p>
                        <p></p>
                    </div>

            </div>
        </div>

        <!-- Laporan Penjualan -->
        <div class="laporan">
            <h2>Laporan Penjualan</h2>
            <div class="lap">
                    <div class="laporan-item">
                        <p></p>
                        <p></p>
                    </div>
              
            </div>
        </div>

    </div>
</body>
</html>
