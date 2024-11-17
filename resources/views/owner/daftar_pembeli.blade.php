<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Pembeli</title>
    <link rel="stylesheet" href="{{ asset('css/daftar_kasir.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
            <div class="nav">
                <div class="left">
                    <a href="#">Home</a>
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
                        <button type="submit" class="btn btn-link" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a> 
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Judul Daftar Kasir -->
        <div class="table-title">Daftar Pembeli</div>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" placeholder="Cari..." class="search-input">
            <button class="search-button">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <!-- Tabel -->
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->CustomerName }}</td>
                        <td>{{ $customer->user->email ?? 'Tidak ada email' }}</td>
                        <td>{{ $customer->CustomerContact }}</td>
                        <td>{{ $customer->CustomerAddress }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
