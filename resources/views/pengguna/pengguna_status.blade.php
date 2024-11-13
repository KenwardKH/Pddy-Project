<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kasir Home</title>
        <link rel="stylesheet" href="{{asset('css/kasir_status.css')}}">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="{{asset('images/logo.png')}}" alt="logo">
                <div class="nav">
                    <div class="left">
                        <a href="home">Home</a>
                        <a href="#">Profil</a>   
                    </div>
                    <div class="right">
                        <a href="buat-pesanan">Buat Pesanan <i class="bi bi-cart"></i></a>
                        <a href="stock-barang">Stock Barang <i class="bi bi-box-seam"></i></a>
                        <a href="konfirmasi">Konfirmasi Pesanan <i class="bi bi-clipboard-check"></i></a>
                        <a href="status">Status Pesanan <i class="bi bi-journal-text"></i></a>
                        <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
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
                <h1>Status Pesanan</h1>

            </div>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Detail</th>
                                <th>Total Harga</th>
                                <th>Pembayaran</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Cetak Invoice</th>
                                <th>Tanggal Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice['InvoiceID'] }}</td>
                                    <td><button class="cetak-button">Cetak</button></td>
                                    <td>{{ $invoice->transactionLog ? 'Rp ' . number_format($invoice->transactionLog->TotalAmount, 0, ',', '.') . ',-' : 'N/A' }}</td>
                                    <td>{{ $invoice['InvoiceDate'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</body>
</html>