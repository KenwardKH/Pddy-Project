<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner_pembelian.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-pagination.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
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
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            margin: 0;
        }

        .modal-header .close-button {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: bold;
            color: #555;
            cursor: pointer;
            transition: color 0.2s;
        }

        .modal-info {
            display: flex;
            flex-direction: column;
            align-items: start;
            margin-left: 20px
        }

        .modal-info p {
            margin: 0;
            margin-top: 10px
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
            background-color: blue;
        }

        table.details-table tr:nth-child(even) {
            background-color: #f7f2f2d5;
        }

        table.details-table tr:hover {
            background-color: #ddcbcbdb;
        }

        /* Styling untuk form filter */
        .filter {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter form label {
            font-weight: bold;
            margin-right: 5px;
            font-size: 14px;
        }

        form input[type="text"],
        form input[type="date"],
        form select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }

        .filter form button {
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter button:hover {
            background-color: #0056b3;
        }

        .filter .btn-secondary {
            background-color: #6c757d;
        }

        .filter .btn-secondary:hover {
            background-color: #565e64;
        }
    </style>
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
            <h1 style="margin: 0;">Riwayat Transaksi</h1>
        </div>
        <div style="margin: 20px; display: flex; justify-content: center;">
            <form method="GET" action="{{ route('owner.riwayatTransaksi') }}" class="filter">
                <div style="column-gap: 20px; display:flex">
                    <div>
                        <label for="customerName">Nama Pemesan:</label>
                        <input type="text" id="customerName" name="customerName"
                            value="{{ request('customerName') }}" placeholder="Cari Pelanggan...">
                    </div>
                    <div>
                        <label for="startDate">Tanggal Mulai:</label>
                        <input type="date" id="startDate" name="startDate" value="{{ request('startDate') }}">
                    </div>
                    <div>
                        <label for="endDate">Tanggal Akhir:</label>
                        <input type="date" id="endDate" name="endDate" value="{{ request('endDate') }}">
                    </div>
                    <div>
                        <label for="orderStatus">Status Pesanan:</label>
                        <select id="orderStatus" name="orderStatus">
                            <option value="">Pilih Status</option>
                            <option value="menunggu pembayaran"
                                {{ request('orderStatus') == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu
                                Pembayaran</option>
                            <option value="diproses" {{ request('orderStatus') == 'diproses' ? 'selected' : '' }}>
                                Diproses</option>
                            <option value="diantar" {{ request('orderStatus') == 'diantar' ? 'selected' : '' }}>Diantar
                            </option>
                            <option value="menunggu pengambilan"
                                {{ request('orderStatus') == 'menunggu pengambilan' ? 'selected' : '' }}>Menunggu
                                Pengambilan</option>
                            <option value="selesai" {{ request('orderStatus') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="dibatalkan" {{ request('orderStatus') == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn">Filter</button>
                    <a href="{{ route('owner.riwayatTransaksi') }}" class="btn">Reset</a>
                </div>
            </form>
        </div>
        <div class="table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Nama Pemesan</th>
                        <th>Kontak Pemesan</th>
                        <th>Opsi Pembayaran</th>
                        <th>Total Pembayaran</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Nama Kasir</th>
                        <th>Jenis Pesanan</th>
                        <th>Detail</th>
                        <th>Status</th>
                        <th>Tanggal Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction as $data)
                        <tr>
                            <td>{{ $data->InvoiceID }}</td>
                            <td>{{ $data->CustomerName }}</td>
                            <td>{{ $data->CustomerContact }}</td>
                            <td>{{ $data->PaymentOption }}</td>
                            <td>{{ number_format($data->TotalAmount, 2, ',', '.') }}</td>
                            <td>{{ $data->PaymentDate ?? 'N/A' }}</td>
                            <td>{{ $data->CashierName ?? 'N/A' }}</td>
                            <td>
                                @if ($data->CashierName != null)
                                    Offline
                                @else
                                    Online
                                @endif
                            </td>
                            <td>
                                <button class="detail-button" data-id="{{ $data->InvoiceID }}">Detail</button>
                            </td>
                            <td>{{ $data->OrderStatus }}</td>
                            <td>{{ $data->InvoiceDate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px; text-align: center;">
            {{ $transaction->appends(request()->query())->links('vendor.pagination.custom') }}
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
    </script>
</body>

</html>
