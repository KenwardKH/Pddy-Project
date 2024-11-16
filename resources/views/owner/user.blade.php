<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('pengguna.home') }}">Home</a>
                    
                </div>
                <div class="right">
                    <a href="#">Produk <i class="bi bi-box-seam"></i></a>
                    <a href="#">Riwayat Kasir<i class="bi bi-cash-coin"></i></a>
                    <a href="{{ route('owner.user') }}">User<i class="bi bi-person"></i></a>
                    <a href="{{ route('owner.log-transaksi') }}">Transaksi <i class="bi bi-receipt-cutoff"></i></a>
                    <a href="#">Laporan<i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('owner.daftar-supplier') }}">Suplllier<i class="bi bi-shop"></i></a>
                </div>
            </div>
        </div>
    
<!-- user content Content -->
<div class="user-content">
    <h2>Daftar Pengguna</h2>
    <div class="buttons-container">
        <div class="item">
            <a href="{{ route('owner.daftar-costumer') }}" class="btn"> 
                <span>Daftar Pembeli</span>
            </a>
        </div>
        <div class="item">
            <a href="{{ route('owner.daftar-kasir') }}" class="btn">
                <span>Daftar Kasir</span>
            </a>
        </div>
    </div>
</div>
</body>
</html>