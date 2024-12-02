<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_riwayat.css') }}">
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
            max-height: 500px;
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
                    <a href="{{ route('kasir.home') }}">Home</a>
                    <a href="{{ route('kasir.profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('buat-pesanan') }}">Buat Pesanan <i class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('kasir.cart') }}">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('kasir.pembayaran') }}">Pesanan Online <i class="bi bi-cash-stack"></i></a>
                    <a href="{{ route('kasir.stock') }}">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="{{ route('status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('kasir.riwayat') }}">Riwayat Pesanan <i class="bi bi-journal-text"></i></a>
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
            <form action="{{ route('kasir.riwayat.batal') }}" method="GET" class="search-container">
                <div>
                    <input type="text" name="name" placeholder="Cari Nama" value="{{ request('name') }}"
                        class="search-bar">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="search-bar">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="search-bar">
                </div>

                <div>
                    <a href="{{ route('kasir.riwayat.batal') }}" class="btn-reset" style="text-decoration: none;">Reset</a>
                    <button type="submit" class="search-button"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>
            <h1>Pesanan Dibatalkan</h1>
        </div>
        <div style="text-align: right; margin-right:20px">
            <a href="{{ route('kasir.riwayat') }}" class="riwayat">Pesanan Berhasil</i></a>
        </div>
        <div class="table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Nama Pemesan</th>
                        <th>Nomor Telepon</th>
                        <th>Total Harga</th>
                        <th>Opsi Pengantaran</th>
                        <th>Alamat Pengiriman</th>
                        <th>Opsi Pembayaran</th>
                        <th>Alasan Pembatalan</th>
                        <th>Detail Pesanan</th>
                        <th>Status</th>
                        <th>Cetak Invoice</th>
                        <th>Tanggal Pesan</th>
                        <th>Tanggal Dibatalkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->InvoiceID }}</td>
                            <td>{{ $invoice->customerName }}</td>
                            <td>{{ $invoice->customerContact }}</td>
                            <td>{{ $invoice->totalAmount ?? 'N/A' }}</td>
                            <td>
                                @if ($invoice->type == 'delivery')
                                    Diantar
                                @elseif ($invoice->type == 'pickup')
                                    Ambil Sendiri
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $invoice->deliveryStatus->alamat ?? 'N/A' }}</td>
                            <td>{{ $invoice->payment_option ?? 'N/A' }}</td>
                            <td>{{ $invoice->cancelledTransaction->cancellation_reason ?? 'N/A' }}</td>
                            <td><button class="detail-button" data-id="{{ $invoice->InvoiceID }}">Detail</button></td>
                            <td>
                                @if ($invoice->deliveryStatus)
                                    {{ $invoice->deliveryStatus->status }}
                                @elseif($invoice->pickupStatus)
                                    {{ $invoice->pickupStatus->status }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td><button class="cetak-button">Cetak</button></td>
                            <td>{{ $invoice->deliveryStatus->created_at ?? ($invoice->pickupStatus->created_at ?? 'N/A') }}
                            </td>
                            <td>{{ $invoice->cancelledTransaction->cancellation_date ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cetak-button').forEach(button => {
                button.addEventListener('click', function() {
                    const invoiceId = this.closest('tr').querySelector('td:first-child').textContent
                        .trim();
                    window.open(`/invoices/${invoiceId}/print`, '_blank');
                });
            });
        });
    </script>
</body>

</html>
