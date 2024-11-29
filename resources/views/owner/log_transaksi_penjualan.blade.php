<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Kasir</title>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/log_transaksi.css') }}">
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
                    <a href="#">Produk <i class="bi bi-box-seam"></i></a>
                    <a href="#">Riwayat Kasir<i class="bi bi-cash-coin"></i></a>
                    <a href="{{ route('owner.user') }}">User<i class="bi bi-person"></i></a>
                    <a href="{{ route('owner.log-transaksi') }}">Transaksi <i class="bi bi-receipt-cutoff"></i></a>
                    <a href="#">Laporan<i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('owner.daftar-supplier') }}">Suplllier<i class="bi bi-shop"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-link" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a> 
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Judul Daftar Kasir -->
        <div class="table-title">Log Transaksi Penjualan</div>

        <!-- Search Bar dan Tanggal -->
        <div class="search-date-container">
            <input type="date" class="date-input" placeholder="Dari">
            <span> - </span>
            <input type="date" class="date-input" placeholder="Sampai">
        </div>

        <!-- Tabel -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Invoice</th>
                    <th>Nama Pembeli</th>
                    <th>Tanggal Transaksi</th>
                    <th>Kasir</th>
                    <th>Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>1000</td>
                    <td>John Doe</td>
                    <td>999</td>
                    <td>08</td>
                    <td>700000</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>