<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Kasir</title>
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
                    <a href="{{ route('pengguna.home') }}">Home</a>
                </div>
                <div class="right">
                    <a href="#">Produk <i class="bi bi-box-seam"></i></a>
                    <a href="#">Riwayat Kasir<i class="bi bi-cash-coin"></i></a>
                    <a href="{{ route('owner.user') }}">User<i class="bi bi-person"></i></a>
                    <a href="{{ route('owner.log-transaksi') }}">Transaksi <i class="bi bi-receipt-cutoff"></i></a>
                    <a href="#">Laporan<i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('owner.daftar-supplier') }}">Supplier<i class="bi bi-shop"></i></a>
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
        <div class="table-title">Daftar Kasir</div>

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
                    <th>No</th>
                    <th>Nama Kasir</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kasirs as $index => $kasir)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kasir->nama_kasir }}</td>
                        <td>{{ $kasir->kontak_kasir }}</td>
                        <td>{{ $kasir->alamat_kasir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   <!-- Tambahkan di atas tabel -->
   <div class="button-container">
    <button class="add-button" id="add-supplier-button">Tambah Supplier</button>
</div>


<!-- Form Pop-up -->
<div id="add-supplier-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" id="close-modal">&times;</span>
        <h2>Tambah Kasir Baru</h2>
        <form id="add-supplier-form" method="POST" action="{{ route('owner.add-kasir') }}">
            @csrf
            <div class="form-group">
                <label for="SupplierName">Nama Kasir</label>
                <input type="text" name="nama_kasir" id="SupplierName" required>
            </div>
            <div class="form-group">
                <label for="SupplierContact">No HP</label>
                <input type="text" name="kontak_kasir" id="SupplierContact" required>
            </div>
            <div class="form-group">
                <label for="SupplierContact">Email</label>
                <input type="text" name="email" id="SupplierContact" required>
            </div>
            <div class="form-group">
                <label for="SupplierAddress">Alamat</label>
                <input type="text" name="alamat_kasir" id="SupplierAddress" required>
            </div>
            <button type="submit" class="submit-button">Tambah</button>
        </form>
    </div>
</div>


<!-- Pop-up Success Modal -->
<div id="success-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" id="close-success-modal">&times;</span>
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h2>Kasir Berhasil Ditambahkan</h2>
    </div>
</div>




<script>
    document.getElementById('add-supplier-button').addEventListener('click', function() {
        document.getElementById('add-supplier-modal').style.display = 'flex';
    });

    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('add-supplier-modal').style.display = 'none';
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada pesan sukses dari server
        @if(session('success'))
            const successModal = document.getElementById('success-modal');
            successModal.style.display = 'flex';

            // Tutup modal setelah 3 detik
            setTimeout(() => {
                successModal.style.display = 'none';
            }, 3000);

            // Tombol tutup manual
            document.getElementById('close-success-modal').addEventListener('click', function () {
                successModal.style.display = 'none';
            });
        @endif
    });
</script>

</body>
</html>
