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
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor Telepon</th>
                                <th>Detail</th>
                                <th>Total Harga</th>
                                <th>Alamat</th>
                                <th>Pengantaran</th>
                                <th>Pembayaran</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Cetak Invoice</th>
                                <th>Tanggal Pesan</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Adam Irawan</td>
                                <td>081234567890</td>
                                <td><a href="#" class="detail-link">Detail</a></td>
                                <td>1750000</td>
                                <td>Jln Universitas No.4, Medan</td>
                                <td>Diantar</td>
                                <td>Cash</td>
                                <td>-</td>
                                <td>
                                    <select class="status-dropdown" onchange="changeColor(this)">
                                        <option value="Diproses">Diproses</option>
                                        <option value="Diantar">Diantar</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </td>
                                <td><button class="cetak-button">Cetak</button></td>
                                <td>10/07/2024</td>
                                <td><button class="delete-button"><i class="bi bi-x-square"></i></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Budi Setiawan</td>
                                <td>081273842590</td>
                                <td><a href="#" class="detail-link">Detail</a></td>
                                <td>2500000</td>
                                <td>Jln Majapahit No.6, Medan</td>
                                <td>Ambil Sendiri</td>
                                <td>Transfer</td>
                                <td>-</td>
                                <td>
                                    <select class="status-dropdown" onchange="changeColor(this)">
                                        <option value="Diproses">Diproses</option>
                                        <option value="Diantar">Diantar</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </td>
                                <td><button class="cetak-button">Cetak</button></td>
                                <td>21/08/2024</td>
                                <td><button class="delete-button"><i class="bi bi-x-square"></i></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Udin Yanto</td>
                                <td>08124554620</td>
                                <td><a href="#" class="detail-link">Detail</a></td>
                                <td>1600000</td>
                                <td>Jln Kesawan No 4A, Medan</td>
                                <td>Ambil Sendiri</td>
                                <td>Kredit</td>
                                <td>20/12/2024</td>
                                <td>
                                    <select class="status-dropdown" onchange="changeColor(this)">
                                        <option value="Diproses">Diproses</option>
                                        <option value="Diantar">Diantar</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </td>
                                <td><button class="cetak-button">Cetak</button></td>
                                <td>11/10/2024</td>
                                <td><button class="delete-button"><i class="bi bi-x-square"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            function changeColor(selectElement) {
                switch (selectElement.value) {
                    case "Diproses":
                        selectElement.style.backgroundColor = "#ffeb3b"; // Yellow
                        selectElement.style.color = "#333";
                        break;
                    case "Diantar":
                        selectElement.style.backgroundColor = "#03a9f4"; // Light Blue
                        selectElement.style.color = "#fff";
                        break;
                    case "Selesai":
                        selectElement.style.backgroundColor = "#4caf50"; // Green
                        selectElement.style.color = "#fff";
                        break;
                    case "Dibatalkan":
                        selectElement.style.backgroundColor = "#f44336"; // Red
                        selectElement.style.color = "#fff";
                        break;
                    default:
                        selectElement.style.backgroundColor = "#fff"; // Default
                        selectElement.style.color = "#333";
                }
            }

            // Initialize colors on page load
            document.querySelectorAll(".status-dropdown").forEach(changeColor);
        </script>
    </body>
</html>