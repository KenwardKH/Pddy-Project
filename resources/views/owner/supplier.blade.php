<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Supplier</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner_product.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        button,
        input,
        optgroup,
        select,
        textarea {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            font-size: medium;
            line-height: normal;
        }

        .btn-link:hover {
            color: #80f4ac;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
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
                    <a href="{{ route('owner.daftarSupply') }}">Riwayat Pembelian Supply <i
                            class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('owner.daftar-costumer') }}">User<i class="bi bi-person"></i></a>
                    <a href="{{ route('owner.riwayatTransaksi') }}">Riwayat Transaksi <i
                            class="bi bi-receipt-cutoff"></i></a>
                    <a href="{{ route('owner.laporanPenjualan') }}">Laporan Penjualan<i
                            class="bi bi-journal-text"></i></a>
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
        <div class="dashboard">
            <h1 style="font-weight: bold">Daftar Supplier</h1>

            <!-- Search Bar -->
            <div class="search-container">
                <form action="{{ route('owner.supplier.search') }}" method="GET">
                    <input type="text" name="query" placeholder="Cari..." class="search-input"
                        style="width: 200px;" value="{{ request('query') }}">
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div style="display: flex; justify-content:right; margin:10px ;margin-right:30px">
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    + Tambah Supplier
                </button>
            </div>

            <!-- Modal for Adding Product -->
            <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('owner.store-supplier') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addSupplierLabel">Tambah Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Product Name -->
                                <div class="form-group">
                                    <label for="SupplierName">Nama Supplier</label>
                                    <input type="text" id="SupplierName" name="SupplierName" class="form-control"
                                        required>
                                </div>

                                <!-- Selling Price -->
                                <div class="form-group mt-3">
                                    <label for="SupplierContact">Kontak Supplier</label>
                                    <input type="number" id="SupplierContact" name="SupplierContact"
                                        class="form-control" required>
                                </div>

                                <!-- Product Unit -->
                                <div class="form-group mt-3">
                                    <label for="SupplierAddress">Alamat Supplier</label>
                                    <input type="text" id="SupplierAddress" name="SupplierAddress"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah Supplier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Supplier</th>
                            <th>Kontak Supplier</th>
                            <th>Alamat</th>
                            <th class="edit">Edit</th>
                            <th class="hapus">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $index => $supplier)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $supplier->SupplierName }}</td>
                                <td>{{ $supplier->SupplierContact }}</td>
                                <td>{{ $supplier->SupplierAddress }}</td>
                                <td class="edit">
                                    <!-- Tombol untuk membuka modal -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editSupplierModal{{ $supplier->SupplierID }}">
                                        <i class="bi bi-pencil btn-edit"></i>
                                    </button>
                                </td>
                                <td class="hapus">
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-id="{{ $supplier->SupplierID }}">
                                        <i class="bi bi-trash btn-danger"></i>
                                    </button>
                                    <form id="delete-form-{{ $supplier->SupplierID }}"
                                        action="{{ route('owner.supplier.destroy', $supplier->SupplierID) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            <!-- Modal untuk edit produk -->
                            <div class="modal fade" id="editSupplierModal{{ $supplier->SupplierID }}" tabindex="-1"
                                aria-labelledby="editSupplierLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('owner.update-supplier', $supplier->SupplierID) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editSupplierLabel">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="SupplierName">Nama Produk</label>
                                                    <input type="text" id="SupplierName" name="SupplierName"
                                                        class="form-control" value="{{ $supplier->SupplierName }}"
                                                        required>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="SupplierContact">Kontak Supplier</label>
                                                    <input type="tel" id="SupplierContact" name="SupplierContact"
                                                        class="form-control" value="{{ $supplier->SupplierContact }}"
                                                        required>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="SupplierAddress">Alamat</label>
                                                    <input type="text" id="SupplierAddress" name="SupplierAddress"
                                                        class="form-control" value="{{ $supplier->SupplierAddress }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Menampilkan modal
        const modalButtons = document.querySelectorAll('[data-modal-target]');
        modalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-target');
                document.querySelector(modalId).style.display = 'block';
            });
        });

        // Menutup modal
        const closeButtons = document.querySelectorAll('[data-modal-close]');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-close');
                document.querySelector(modalId).style.display = 'none';
            });
        });

        // Menutup modal jika klik di luar modal
        window.addEventListener('click', (event) => {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const supplierId = this.getAttribute('data-id');
                    const form = document.getElementById(`delete-form-${supplierId}`);

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data supplier akan dihapus secara permanen!",
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
