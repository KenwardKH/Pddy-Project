<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_status.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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
            margin-top: 40px
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
        <div class="dashboard">
            <h1>Status Pesanan</h1>

            <!-- Toggle Buttons -->
            <div class="button-container">
                <a href="{{ route('status', 'delivery') }}"
                    class="toggle-button  {{ $type === 'delivery' ? 'active' : '' }}">Pesanan Diantar</a>
                <a href="{{ route('status', 'pickup') }}"
                    class="toggle-button  {{ $type === 'pickup' ? 'active' : '' }}">Ambil Sendiri</a>
            </div>
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
                            <th>Nomor Telepon</th>
                            <th>Jumlah Produk</th>
                            <th>Total Harga</th>
                            @if ($type !== 'pickup')
                                <th>Alamat Pengiriman</th>
                            @endif
                            <th>Opsi Pembayaran</th>
                            <th>Detail Pesanan</th>
                            <th>Status</th>
                            <th>Cetak Invoice</th>
                            <th>Tanggal Pesan</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            @php
                                $type = $invoice->deliveryStatus
                                    ? 'delivery'
                                    : ($invoice->pickupStatus
                                        ? 'pickup'
                                        : 'N/A');
                            @endphp
                            @if ($invoice->type === 'delivery')
                                <tr>
                                    <td>{{ $invoice->InvoiceID }}</td>
                                    <td>{{ $invoice->customerName }}</td>
                                    <td>{{ $invoice->customerContact }}</td>
                                    <td>{{ $invoice->invoiceDetails->sum('Quantity') }}</td>
                                    <td>{{ number_format($invoice->totalAmount ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $invoice->deliveryStatus->alamat ?? 'N/A' }}</td>
                                    <td>{{ $invoice->payment_option ?? 'N/A' }}</td>
                                    <td>
                                        <button class="detail-button"
                                            data-id="{{ $invoice->InvoiceID }}">Detail</button>
                                    </td>
                                    <td>
                                        <form action="{{ route('status.next', $invoice->InvoiceID) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $invoice->type }}">
                                            <button type="submit" class="btn-status">
                                                @if ($invoice->type === 'delivery')
                                                    {{ $invoice->deliveryStatus->status ?? 'diproses' }}
                                                @elseif ($invoice->type === 'pickup')
                                                    {{ $invoice->pickupStatus->status ?? 'diproses' }}
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td><button class="cetak-button">Cetak</button></td>
                                    <td>{{ $invoice->deliveryStatus->created_at ?? ($invoice->pickupStatus->created_at ?? 'N/A') }}
                                    </td>
                                    <td>
                                        <button class="batal" data-id="{{ $invoice->InvoiceID }}"
                                            data-type="{{ $invoice->type }}">
                                            Batal
                                        </button>
                                    </td>
                                </tr>
                            @elseif ($invoice->type === 'pickup')
                                <tr>
                                    <td>{{ $invoice->InvoiceID }}</td>
                                    <td>{{ $invoice->customerName }}</td>
                                    <td>{{ $invoice->customerContact }}</td>
                                    <td>{{ $invoice->invoiceDetails->sum('Quantity') }}</td>
                                    <td>Rp {{ number_format($invoice->totalAmount ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $invoice->payment_option ?? 'N/A' }}</td>
                                    <td>
                                        <button class="detail-button"
                                            data-id="{{ $invoice->InvoiceID }}">Detail</button>
                                    </td>
                                    <td>
                                        <form action="{{ route('status.next', $invoice->InvoiceID) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $invoice->type }}">
                                            <button type="submit" class="btn-status">
                                                @if ($invoice->type === 'delivery')
                                                    {{ $invoice->deliveryStatus->status ?? 'diproses' }}
                                                @elseif ($invoice->type === 'pickup')
                                                    {{ $invoice->pickupStatus->status ?? 'diproses' }}
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td><button class="cetak-button">Cetak</button></td>
                                    <td>{{ $invoice->InvoiceDate }}</td>
                                    <td>
                                        <button class="batal" data-id="{{ $invoice->InvoiceID }}"
                                            data-type="{{ $invoice->type }}">
                                            Batal
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
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

        document.querySelectorAll('.btn-status').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah pengiriman form langsung
                const form = this.closest('form'); // Ambil form terdekat
                const status = this.textContent.trim(); // Ambil status tombol

                Swal.fire({
                    title: 'Konfirmasi Perubahan Status',
                    text: `Apakah Anda yakin ingin mengubah status pesanan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form jika dikonfirmasi
                        form.submit();
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cetak-button').forEach(button => {
                button.addEventListener('click', function() {
                    const invoiceId = this.closest('tr').querySelector('td:first-child').textContent
                        .trim();
                    window.open(`/invoices/${invoiceId}/print`, '_blank');
                });
            });
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
