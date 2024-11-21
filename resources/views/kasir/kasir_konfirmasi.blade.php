<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_konfirmasi.css') }}">
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
                <select class="category-dropdown" name="category">
                    <option value="">Semua Kategori</option>
                    <option value="kategori1">Kategori 1</option>
                    <option value="kategori2">Kategori 2</option>
                    <option value="kategori3">Kategori 3</option>
                </select>
                <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
            </form>
            <h1>Daftar Pesanan Online</h1>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelanggan</th>
                            <th>Nomor Telepon</th>
                            <th>Detail</th>
                            <th>Total Harga</th>
                            <th class="alamat">Alamat</th>
                            <th>Pengantaran</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Tolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>John Doe</td>
                            <td>081234567890</td>
                            <td><a href="#" class="detail-link">Detail</a></td>
                            <td>750000</td>
                            <td>Jln Skip No.14, Medan</td>
                            <td>Diantar</td>
                            <td>Cash</td>
                            <td><button class="confirm-button">Konfirmasi</button></td>
                            <td><button class="delete-button"><i class="bi bi-x-square"></i></button></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Jane Johnson</td>
                            <td>03835757284</td>
                            <td><a href="#" class="detail-link">Detail</a></td>
                            <td>1500000</td>
                            <td>Jln Adam Malik No.3, Medan</td>
                            <td>Ambil Sendiri</td>
                            <td>Transfer</td>
                            <td><button class="confirm-button">Konfirmasi</button></td>
                            <td><button class="delete-button"><i class="bi bi-x-square"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
