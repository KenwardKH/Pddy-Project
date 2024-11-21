<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
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
            <form action="" method="GET" class="search-container">
                <input type="text" placeholder="Cari produk..." class="search-bar" name="search">
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
                            <th>Kategori</th>
                            <th class="deskripsi">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td><img src="{{ asset('images/produk/pensil2b.png') }}" alt="Pencil Ajaib 2b"></td>
                            <td>Pensil Ajaib 2b</td>
                            <td>25000</td>
                            <td>60</td>
                            <td>lusin</td>
                            <td>Alat Tulis</td>
                            <td>Pensil Ajaib 2B adalah pensil berkualitas tinggi dengan tingkat kekerasan lembut, ideal
                                untuk menggambar dan menulis</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td><img src="{{ asset('images/produk/penghapus2b.png') }}" alt="Penghapus Ajaib 2b"></td>
                            <td>Penghapus Ajaib 2b</td>
                            <td>25000</td>
                            <td>70</td>
                            <td>lusin</td>
                            <td>Alat Tulis</td>
                            <td>Penghapus Ajaib 2B adalah penghapus berkualitas tinggi yang efektif menghapus pensil
                                tanpa merusak kertas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
