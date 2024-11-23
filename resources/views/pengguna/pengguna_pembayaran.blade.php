<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/pengguna_pembayaran.css') }}">
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

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('pengguna.home') }}">Home</a>
                    <a href="{{ route('profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('pengguna.buat_pesanan') }}">Beli Barang <i class="bi bi-bag-plus"></i></a>
                    <a href="/keranjang">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('pengguna.pembayaran') }}">Daftar Pembayaran <i class="bi bi-cash-stack"></i></a>
                    <a href="{{ route('pengguna.status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('pengguna.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
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
        <h1 class="dashboard">Daftar Pembayaran</h1>
        <!-- Your existing table -->
        <div class="table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
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
                            <td><button class="detail-button" data-id="{{ $invoice->InvoiceID }}">Detail</button></td>
                            <td>{{ $invoice->type == 'delivery' ? 'Diantar' : ($invoice->type == 'pickup' ? 'Ambil Sendiri' : 'N/A') }}
                            </td>
                            <td>{{ $invoice->deliveryStatus->alamat ?? 'N/A' }}</td>
                            <td>{{ $invoice->totalAmount ?? 'N/A' }}</td>
                            <td>
                                @if ($invoice->payment && $invoice->payment->PaymentImage)
                                    <img class="bukti_tf"
                                        src="{{ asset('images/bukti_transfer/' . $invoice->payment->PaymentImage) }}"
                                        alt="bukti_tf">
                                @else
                                    <button class="pembayaran">Bayar</button>
                                @endif
                            </td>
                            <td>{{ $invoice->deliveryStatus->status ?? ($invoice->pickupStatus->status ?? 'N/A') }}
                            </td>
                            <td>{{ $invoice->lastPay }}</td>
                            <td>{{ $invoice->InvoiceDate }}</td>
                            <td><button class="batal">Batal</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modal Pembayaran -->
        <div class="overlay" id="payment-overlay"></div>
        <div class="modal" id="payment-modal">
            <div class="modal-header">
                <h2>Pembayaran</h2>
                <button class="close-button">&times;</button>
            </div>
            <div class="modal-body">
                <h3>Rekening yang Digunakan:</h3>
                <div class="payment-methods">
                    <div class="bank">
                        <img src="{{ asset('images/pembayaran/bca.png') }}" alt="BCA Logo">
                        <div class="bank-details">
                            <p><strong>Nomor Rekening:</strong> 1234567890</p>
                            <p><strong>Nama:</strong> Toko ATK Sinar Pelangi</p>
                        </div>
                    </div>
                    <div class="bank">
                        <img src="{{ asset('images/pembayaran/ovo.png') }}" alt="OVO Logo">
                        <div class="bank-details">
                            <p><strong>Nomor Rekening:</strong> 1234567890</p>
                            <p><strong>Nama:</strong> Toko ATK Sinar Pelangi</p>
                        </div>
                    </div>
                    <div class="bank">
                        <img src="{{ asset('images/pembayaran/mandiri.png') }}" alt="Mandiri Logo">
                        <div class="bank-details">
                            <p><strong>Nomor Rekening:</strong> 1234567890</p>
                            <p><strong>Nama:</strong> Toko ATK Sinar Pelangi</p>
                        </div>
                    </div>
                </div>

                <form id="payment-form" action="{{ route('pembayaran.upload') }}" class="bukti-transfer" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="InvoiceID" value="{{ $invoice->InvoiceID }}">
                    <input type="hidden" name="AmountPaid" value="{{ $invoice->totalAmount }}">
                    <div class="form-group">
                        <label for="proof">Unggah Bukti Transfer:</label>
                        <input type="file" id="proof" name="bukti_transfer" accept="image/*" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="submit-button">Kirim Bukti</button>
                    </div>
                </form>
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

        document.querySelectorAll('.pembayaran').forEach(button => {
            button.addEventListener('click', function() {
                // Show payment modal
                document.getElementById('payment-modal').classList.add('show');
                document.getElementById('payment-overlay').classList.add('show');
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
    </script>
</body>

</html>
