<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Supplier</title>
    <link rel="stylesheet" href="{{ asset('css/supplier.css') }}">
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

        <!-- Judul Daftar Supplier -->
        <div class="table-title">Daftar Supplier</div>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" placeholder="Cari..." class="search-input" id="search-input">
            <button class="search-button" id="search-button">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <!-- Tabel -->
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Supplier</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $index => $supplier)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $supplier->SupplierName }}</td>
                        <td>{{ $supplier->SupplierContact }}</td>
                        <td>{{ $supplier->SupplierAddress }}</td>
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
        <h2>Tambah Supplier Baru</h2>
        <form id="add-supplier-form" method="POST" action="{{ route('owner.add-supplier') }}">
            @csrf
            <div class="form-group">
                <label for="SupplierName">Nama Supplier</label>
                <input type="text" name="SupplierName" id="SupplierName" required>
            </div>
            <div class="form-group">
                <label for="SupplierContact">No HP</label>
                <input type="text" name="SupplierContact" id="SupplierContact" required>
            </div>
            <div class="form-group">
                <label for="SupplierAddress">Alamat</label>
                <input type="text" name="SupplierAddress" id="SupplierAddress" required>
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
        <h2>Supplier Berhasil Ditambahkan</h2>
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
