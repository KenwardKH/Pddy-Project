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
                    <a href="{{ route('owner.daftar-costumer') }}">User<i class="bi bi-person"></i></a>
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
        <div class="table-title">Daftar Kasir</div>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" placeholder="Cari..." class="search-input">
            <button class="search-button">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <!-- Tambahkan di atas tabel -->
        <div class="button-add">
            <button class="add-button" id="add-kasir-button">+ Tambah Kasir</button>
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
                    <th>Edit</th>
                    <th>Hapus</th>
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
                        <td class="edit">
                            <button type="button" class="btn btn-primary edit-btn" data-id="{{ $kasir->id_kasir }}"
                                data-nama="{{ $kasir->nama_kasir }}" data-kontak="{{ $kasir->kontak_kasir }}"
                                data-email="{{ $kasir->user->email }}" data-alamat="{{ $kasir->alamat_kasir }}">
                                <i class="bi bi-pencil btn-edit"></i>
                            </button>
                        </td>

                        <td class="hapus">
                            <button type="button" class="btn btn-danger delete-btn" data-id="{{ $kasir->id_kasir }}">
                                <i class="bi bi-trash btn-danger"></i>
                            </button>
                            <form id="delete-form-{{ $kasir->id_kasir }}"
                                action="{{ route('owner.kasir.destroy', $kasir->id_kasir) }}" method="POST"
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

    <!-- Form Pop-up -->
    <div id="add-kasir-modal" class="modal" style="display: none;">
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

            <form id="add-kasir-form" method="POST" action="{{ route('owner.add-kasir') }}">
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

    <!-- Update Kasir Modal -->
    <div id="update-kasir-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button" id="close-update-modal">&times;</span>
            <h2>Update Kasir</h2>

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

            <form id="update-kasir-form" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_kasir" id="update-id-kasir">
                <div class="form-group">
                    <label for="UpdateNamaKasir">Nama Kasir</label>
                    <input type="text" name="nama_kasir" id="UpdateNamaKasir" required>
                </div>
                <div class="form-group">
                    <label for="UpdateNoHP">No HP</label>
                    <input type="text" name="kontak_kasir" id="UpdateNoHP" required>
                </div>
                <div class="form-group">
                    <label for="UpdateEmailKasir">Email</label>
                    <input type="email" name="email" id="UpdateEmailKasir" required>
                </div>
                <div class="form-group">
                    <label for="UpdateAlamatKasir">Alamat</label>
                    <input type="text" name="alamat_kasir" id="UpdateAlamatKasir" required>
                </div>
                <button type="submit" class="submit-button">Update</button>
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
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const kasirId = this.getAttribute('data-id');
                    const form = document.getElementById(`delete-form-${kasirId}`);

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data kasir akan dihapus secara permanen!",
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

        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada pesan error dari server
            @if ($errors->any())
                document.getElementById('add-kasir-modal').style.display = 'flex';
            @endif
        });

        document.getElementById('add-kasir-button').addEventListener('click', function() {
            document.getElementById('add-kasir-modal').style.display = 'flex';
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('add-kasir-modal').style.display = 'none';
        });

        //Edit Kasir
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            const updateModal = document.getElementById('update-kasir-modal');
            const closeUpdateModal = document.getElementById('close-update-modal');
            const updateForm = document.getElementById('update-kasir-form');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const kasirId = this.getAttribute('data-id');
                    const namaKasir = this.getAttribute('data-nama');
                    const kontakKasir = this.getAttribute('data-kontak');
                    const emailKasir = this.getAttribute('data-email');
                    const alamatKasir = this.getAttribute('data-alamat');

                    // Populate form fields
                    document.getElementById('update-id-kasir').value = kasirId;
                    document.getElementById('UpdateNamaKasir').value = namaKasir;
                    document.getElementById('UpdateNoHP').value = kontakKasir;
                    document.getElementById('UpdateEmailKasir').value = emailKasir;
                    document.getElementById('UpdateAlamatKasir').value = alamatKasir;

                    // Update the form action
                    updateForm.action = `/owner/kasir/${kasirId}`;

                    // Show the modal
                    updateModal.style.display = 'flex';
                });
            });

            closeUpdateModal.addEventListener('click', function() {
                updateModal.style.display = 'none';
            });
        });
    </script>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successModal = document.getElementById('success-modal');
                successModal.style.display = 'flex';

                const successMessage = @json(session('success')); // Ambil pesan dari session
                document.querySelector('#success-modal h2').innerText = successMessage; // Tampilkan sesuai

                // Tutup modal setelah 3 detik
                setTimeout(() => {
                    successModal.style.display = 'none';
                }, 3000);
            });
        </script>
    @endif


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
