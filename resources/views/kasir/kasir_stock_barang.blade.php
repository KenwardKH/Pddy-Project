<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Barang</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_stock_barang.css') }}">
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
                    <a href="{{ route('kasir.profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('buat-pesanan') }}">Buat Pesanan <i class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('kasir.cart') }}">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('kasir.pembayaran') }}">Pesanan Online <i class="bi bi-cash-stack"></i></a>
                    <a href="{{ route('kasir.stock') }}">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="{{ route('status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('kasir.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
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
            <form action="{{ route('kasir.stock') }}" method="GET" class="search-container">
                <input type="text" placeholder="Cari produk..." class="search-bar" name="search" value="{{ request('search') }}">
                <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
            </form>
            
            <h1>Stock Barang</h1>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Gambar Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th class="deskripsi">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td>
                                    <img src="{{ asset('images/produk/' . $product->image) }}" alt="{{ $product->ProductName }}">
                                </td>
                                <td>{{ $product->ProductName }}</td>
                                <td>{{ $product->pricing->UnitPrice ?? '-' }}</td>
                                <td>{{ $product->CurrentStock }}</td>
                                <td>{{ $product->ProductUnit }}</td>
                                <td>{{ $product->Description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
