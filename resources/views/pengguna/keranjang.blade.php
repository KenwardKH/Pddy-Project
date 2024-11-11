<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('pengguna.home') }}">Home</a>
                    <a href="{{ route('profile.show')}}">Profil</a>
                </div>
                <div class="right">
                    <a href="#">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="#">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="#">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                    <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </div>
    
        <h1>Keranjang Belanja</h1>
        @if(!empty($cart) && count($cart) > 0)
                @foreach($cart as $item)
                    <li>
                        {{ $item['name'] ?? 'Produk Tidak Diketahui' }} - 
                        Jumlah: {{ $item['quantity'] ?? 0 }}
                    </li>
                @endforeach
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
    
</body>
</html>