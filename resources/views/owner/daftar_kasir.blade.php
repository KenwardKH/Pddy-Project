<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Kasir</title>
    <link rel="stylesheet" href="{{ asset('css/daftar_kasir.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
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
                    <a href="{{ route('owner.daftar-supplier') }}">Supplier<i class="bi bi-shop"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-link"
                            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
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

        <!-- Tambahkan di atas tabel -->
        <div class="button-container">
            <button class="add-button" id="add-supplier-button">+ Tambah Kasir</button>
        </div>

        <!-- Tabel -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kasir</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kasirs as $index => $kasir)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kasir->nama_kasir }}</td>
                        <td>{{ $kasir->kontak_kasir }}</td>
                        <td>{{ $kasir->user->email }}</td>
                        <td>{{ $kasir->alamat_kasir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>




    <!-- Form Pop-up -->
    <div id="add-supplier-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button" id="close-modal">&times;</span>
            <h2>Tambah Kasir Baru</h2>
            <!-- Menampilkan pesan error jika ada -->
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="add-supplier-form" method="POST" action="{{ route('owner.add-kasir') }}">
                @csrf
                <div class="form-group">
                    <label for="NamaKasir">Nama Kasir</label>
                    <input type="text" name="nama_kasir" id="NamaKasir" required>
                </div>
                <div class="form-group">
                    <label for="NoHP">No HP</label>
                    <input type="text" name="kontak_kasir" id="NoHP" required>
                </div>
                <div class="form-group">
                    <label for="EmailKasir">Email</label>
                    <input type="text" name="email" id="EmailKasir" required>
                </div>
                <div class="form-group">
                    <label for="AlamatKasir">Alamat</label>
                    <input type="text" name="alamat_kasir" id="AlamatKasir" required>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" name="password" id="Password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada pesan error dari server
            @if ($errors->any())
                document.getElementById('add-supplier-modal').style.display = 'flex';
            @endif
        });

        document.getElementById('add-supplier-button').addEventListener('click', function() {
            document.getElementById('add-supplier-modal').style.display = 'flex';
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('add-supplier-modal').style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada pesan sukses dari server
            @if (session('success'))
                const successModal = document.getElementById('success-modal');
                successModal.style.display = 'flex';

                // Tutup modal setelah 3 detik
                setTimeout(() => {
                    successModal.style.display = 'none';
                }, 3000);

                // Tombol tutup manual
                document.getElementById('close-success-modal').addEventListener('click', function() {
                    successModal.style.display = 'none';
                });
            @endif
        });
    </script>

</body>

</html>
