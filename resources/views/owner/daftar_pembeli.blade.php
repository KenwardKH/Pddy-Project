<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Pembeli</title>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daftar_kasir.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
        <div class="nav">
            <div class="left">
                <a href="{{ route('owner.home') }}">Home</a>
            </div>
            <div class="right">
                <a href="{{ route('owner.product') }}">Produk <i class="bi bi-box-seam"></i></a>
                <a href="{{ route('owner.daftar-supplier') }}">Supplier<i class="bi bi-shop"></i></a>
                <a href="{{ route('owner.daftarSupply') }}">Riwayat Pembelian Supply <i class="bi bi-bag-plus"></i></a>
                <a href="{{ route('owner.daftar-costumer') }}">User<i class="bi bi-person"></i></a>
                <a href="{{ route('owner.riwayatTransaksi') }}">Riwayat Transaksi <i
                        class="bi bi-receipt-cutoff"></i></a>
                <a href="{{ route('owner.laporanPenjualan') }}">Laporan Penjualan<i class="bi bi-journal-text"></i></a>
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
    <div class="button-container">
        <a href="{{ route('owner.daftar-costumer') }}"
            class="toggle-button {{ request()->routeIs('owner.daftar-costumer') ? 'active' : '' }}">
            Daftar Pelanggan
        </a>
        <a href="{{ route('owner.daftar-kasir') }}"
            class="toggle-button {{ request()->routeIs('owner.daftar-kasir') ? 'active' : '' }}">
            Daftar Kasir
        </a>
    </div>


    <!-- Judul Daftar Kasir -->
    <div class="table-title">Daftar Pelanggan</div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel -->
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Verifikasi</th>
                <th>Hapus</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->CustomerName }}</td>
                    <td>{{ $customer->user->email ?? 'Tidak ada email' }}</td>
                    <td>{{ $customer->CustomerContact }}</td>
                    <td>{{ $customer->CustomerAddress }}</td>
                    <td>{{ $customer->user && $customer->user->email_verified_at ? 'Sudah' : 'Belum' }}</td>
                    <td class="hapus">
                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $customer->CustomerID }}">
                            <i class="bi bi-trash btn-danger"></i>
                        </button>
                        <form id="delete-form-{{ $customer->CustomerID }}"
                            action="{{ route('owner.customer.destroy', $customer->CustomerID) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const customerId = this.getAttribute('data-id');
                    const form = document.getElementById(`delete-form-${customerId}`);

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data pelanggan akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
