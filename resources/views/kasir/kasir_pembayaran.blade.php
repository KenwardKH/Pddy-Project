<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_konfirmasi.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 80%;
            max-width: 800px;
            height: auto;
            max-height: 600px;
            border-radius: 10px;
            overflow: auto
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }


        .modal-footer {
            text-align: right;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .modal.show,
        .overlay.show {
            display: block;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }

        table.details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.details-table th,
        table.details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.details-table th {
            background-color: #f4f4f4;
        }
        .alert-success {
            background-color: #d4edda;
            /* Light green background */
            color: #155724;
            /* Dark green text */
            border: 1px solid #c3e6cb;
            /* Light green border */
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-success i {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('kasir.home') }}">Home</a>
                    <a href="{{ route('kasir.profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('buat-pesanan') }}">Buat Pesanan <i class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('kasir.cart') }}">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('kasir.pembayaran') }}">Pesanan Online <i class="bi bi-cash-stack"></i></a>
                    <a href="{{ route('kasir.stock') }}">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="{{ route('status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('kasir.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
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
        <h1 class="dashboard">Pesanan Online</h1>
        <!-- Your existing table -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Nama Pemesan</th>
                        <th>Telepon</th>
                        <th>Detail</th>
                        <th>Opsi Pengantaran</th>
                        <th>Alamat</th>
                        <th>Total Harga</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Tanggal Terakhir Pembayaran</th>
                        <th>Tanggal Pesan</th>
                        <th>Batal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->InvoiceID }}</td>
                            <td>{{ $invoice->customerName }}</td>
                            <td>{{ $invoice->customerContact }}</td>
                            <td><button class="detail-button" data-id="{{ $invoice->InvoiceID }}">Detail</button></td>
                            <td>{{ $invoice->type == 'delivery' ? 'Diantar' : ($invoice->type == 'pickup' ? 'Ambil Sendiri' : 'N/A') }}
                            </td>
                            <td>{{ $invoice->deliveryStatus->alamat ?? 'N/A' }}</td>
                            <td>{{ number_format($invoice->totalAmount ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if ($invoice->payment && $invoice->payment->PaymentImage)
                                    <img class="bukti_tf"
                                        src="{{ asset('images/bukti_transfer/' . $invoice->payment->PaymentImage) }}"
                                        alt="bukti_tf">
                                @else
                                    Menunggu Pembayaran
                                @endif
                            </td>
                            <td>
                                @if ($invoice->payment && $invoice->payment->PaymentImage)
                                    <form action="{{ route('kasir.konfirmasi', $invoice->InvoiceID) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $invoice->type }}">
                                        <button type="submit" class="konfirmasi">Konfirmasi Pembayaran</button>
                                    </form>
                                @else
                                    {{ $invoice->deliveryStatus->status ?? ($invoice->pickupStatus->status ?? 'N/A') }}
                                @endif
                            </td>
                            <td>{{ $invoice->lastPay }}</td>
                            <td>{{ $invoice->deliveryStatus->created_at ?? ($invoice->pickupStatus->created_at ?? 'N/A') }}
                            </td>
                            <td>
                                <button class="batal" data-id="{{ $invoice->InvoiceID }}"
                                    data-type="{{ $invoice->type }}">
                                    Batal
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal untuk Alasan Pembatalan -->
        <div class="modal-batal">
            <div class="overlay" id="cancel-overlay"></div>
            <div class="modal" id="cancel-modal">
                <div class="modal-header">
                    <h2>Batalkan Pesanan</h2>
                    <button class="close-button" title="Tutup">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="modal-description">Alasan pesanan dibatalkan:</p>
                    <form id="cancel-form" method="POST" action="{{ route('kasir.batal', ['id' => ':id']) }}">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="type" id="cancel-type">
                        <textarea name="reason" id="cancel-reason" rows="4" placeholder="Masukkan alasan pembatalan..." required></textarea>
                        <div class="button-group">
                            <button type="submit" class="submit-button">Konfirmasi Pembatalan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Gambar -->
        <div class="overlay" id="image-overlay"></div>
        <div class="modal" id="image-modal">
            <div class="modal-header">
                <button class="close-button">&times;</button>
            </div>
            <div class="modal-body">
                <img id="modal-image" src="" alt="Preview Gambar" style="max-width: 100%; max-height: 100%;">
            </div>
        </div>


        <!-- Modal -->
        <div class="overlay"></div>
        <div class="modal" id="order-modal">
            <div class="modal-header">
                <h2>Detail Pesanan</h2>
                <button class="close-button">&times;</button>
            </div>
            <div class="modal-body cart-items">
                <div id="modal-content">
                    <!-- Data dynamically inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <h3 id="total-amount"></h3>
                <button class="close-button">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
            }).format(number);
        }
        document.querySelectorAll('.detail-button').forEach(button => {
            button.addEventListener('click', function() {
                const invoiceID = this.dataset.id;

                // Fetch data from the server
                fetch(`/api/invoice/${invoiceID}`)
                    .then(response => response.json())
                    .then(data => {
                        const modalContent = document.getElementById('modal-content');
                        modalContent.innerHTML = '';

                        data.details.forEach(detail => {
                            modalContent.innerHTML += `
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="/images/produk/${detail.productImage}" alt="${detail.productImage}">
                        </div>
                        <div class="item-details">
                            <h2>${detail.product}</h2>
                            <p>Harga: ${formatRupiah(detail.price)}</p>
                        </div>
                        <div class="quantity-control">
                            <h4>Jumlah: </h4>
                            <p style="color: #FFA500; width: 70px;"><b>${detail.Quantity}</b> ${detail.productUnit}</p>
                        </div>
                        <div class="subtotal">
                            Subtotal:
                            ${formatRupiah(detail.total)}.00
                        </div>
                    </div>`;
                        });

                        // Set the total amount in the footer
                        document.getElementById('total-amount').innerHTML =
                            `Total Pesanan: ${formatRupiah(data.totalAmount)}.00`;
                        document.getElementById('order-modal').classList.add('show');
                        document.querySelector('.overlay').classList.add('show');
                    })
                    .catch(error => {
                        console.error("Error fetching order details:", error);
                    });

            });
        });

        document.querySelectorAll('.close-button').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('order-modal').classList.remove('show');
                document.querySelector('.overlay').classList.remove('show');
            });
        });


        document.querySelectorAll('.close-button').forEach(button => {
            button.addEventListener('click', function() {
                // Hide both modals
                document.getElementById('payment-modal').classList.remove('show');
                document.getElementById('payment-overlay').classList.remove('show');
            });
        })

        document.querySelectorAll('.bukti_tf').forEach(img => {
            img.addEventListener('click', function() {
                const modalImage = document.getElementById('modal-image');
                modalImage.src = this.src; // Set src gambar modal ke gambar yang diklik
                document.getElementById('image-modal').classList.add('show');
                document.getElementById('image-overlay').classList.add('show');
            });
        });

        document.querySelector('#image-modal .close-button').addEventListener('click', function() {
            document.getElementById('image-modal').classList.remove('show');
            document.getElementById('image-overlay').classList.remove('show');
        });

        document.getElementById('image-overlay').addEventListener('click', function() {
            document.getElementById('image-modal').classList.remove('show');
            this.classList.remove('show');
        });

        // Batal pesanan

        document.querySelectorAll('.batal').forEach(button => {
            button.addEventListener('click', function() {
                const invoiceID = this.dataset.id;
                const type = this.dataset.type;

                // Tampilkan modal pembatalan
                document.getElementById('cancel-modal').classList.add('show');
                document.getElementById('cancel-overlay').classList.add('show');

                // Set URL dan tipe untuk form pembatalan
                const cancelForm = document.getElementById('cancel-form');
                cancelForm.action = `/kasir/batal/${invoiceID}`;
                document.getElementById('cancel-type').value = type;
            });
        });

        document.querySelectorAll('.close-button').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('cancel-modal').classList.remove('show');
                document.getElementById('cancel-overlay').classList.remove('show');
            });
        });

        document.getElementById('cancel-overlay').addEventListener('click', function() {
            document.getElementById('cancel-modal').classList.remove('show');
            this.classList.remove('show');
        });

        // Menutup modal jika area di luar modal (overlay) diklik
        document.querySelectorAll('.overlay').forEach(overlay => {
            overlay.addEventListener('click', function() {
                document.querySelectorAll('.modal.show').forEach(modal => modal.classList.remove('show'));
                this.classList.remove('show'); // Hapus class "show" pada overlay
            });
        });

        //sweetalert
        document.querySelectorAll('.konfirmasi').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah submit form langsung
                const form = this.closest('form'); // Mengambil form terdekat

                Swal.fire({
                    title: 'Konfirmasi Pesanan',
                    text: 'Apakah Anda yakin ingin mengonfirmasi pesanan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form jika dikonfirmasi
                        form.submit();
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
