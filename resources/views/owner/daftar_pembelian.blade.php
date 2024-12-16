<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat Pembelian Supply</title>
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
            background-color: blue;
        }

        table.details-table tr:nth-child(even) {
            background-color: #f7f2f2d5;
        }

        table.details-table tr:hover {
            background-color: #ddcbcbdb;
        }

        .btn-baru {
            padding: 10px;
            background-color: green;
            margin-right: 60px;
            font-size: 16px;
            color: white;
            font-weight: bold;
            border-radius: 10px
        }

        .btn-baru:hover {
            background-color: rgba(0, 172, 0, 0.841);
            cursor: pointer;
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
            <h1 style="margin: 0;">Daftar Pembelian Supply</h1>
        </div>
        <div style="margin: 20px; display: flex; justify-content: center;">
            <form method="GET" action="{{ route('owner.daftarSupply') }}" class="filter">
                <div style="column-gap: 20px; display:flex">
                    <div>
                        <label for="supplierName">Nama Supplier:</label>
                        <input type="text" id="supplierName" name="supplierName"
                            value="{{ request('supplierName') }}" placeholder="Cari Supplier...">
                    </div>
                    <div>
                        <label for="startDate">Tanggal Mulai:</label>
                        <input type="date" id="startDate" name="startDate" value="{{ request('startDate') }}">
                    </div>
                    <div>
                        <label for="endDate">Tanggal Akhir:</label>
                        <input type="date" id="endDate" name="endDate" value="{{ request('endDate') }}">
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn">Filter</button>
                    <a href="{{ route('owner.daftarSupply') }}" class="btn">Reset</a>
                </div>
            </form>
        </div>
        <div style="display: flex; justify-content:right; margin:10px ;margin-right:30px">
            <form action="{{ route('supplyInvoice.create') }}" method="get">
                <button class="btn btn-baru">
                    + Stock Baru
                </button>
            </form>

        </div>
        <!-- Your existing table -->
        <div class="table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID Invoice Supply</th>
                        <th>Gambar Invoice Supply</th>
                        <th>Nomor Invoice</th>
                        <th>Nama Supplier</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Detail</th>
                        <th>Tanggal Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->SupplyInvoiceId }}</td>
                            <td>
                                @if ($invoice->SupplyInvoiceImage)
                                    <img class="invoice_image"
                                        src="{{ asset('images/supply_invoice_image/' . $invoice->SupplyInvoiceImage) }}"
                                        alt="Gambar Invoice" width="200">
                                @else
                                    <span>Tidak ada gambar</span>
                                @endif
                            </td>
                            <td>{{ $invoice->SupplyInvoiceNumber }}</td>
                            <td>{{ $invoice->SupplierName }}</td>
                            <td>{{ $invoice->supplyInvoiceDetail->count('Quantity') }}</td>
                            <td>{{ number_format($invoice->totalAmount ?? 0, 0, ',', '.') }}</td>
                            <td><button class="detail-button" data-id="{{ $invoice->SupplyInvoiceId }}">Detail</button>
                            </td>
                            <td>{{ $invoice->SupplyDate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        {{-- Pagination --}}
        <div style="margin-top: 20px; text-align: center;">
            {{ $invoices->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>

        <!-- Modal Gambar -->
        <div class="overlay" id="image-overlay"></div>
        <div class="modal" id="image-modal" style="width: 56%; height: 80%; max-width: 1200px;">
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
            <div class="modal-body">
                <table class="details-table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Diskon</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modal-content">
                        <!-- Data dynamically inserted here -->
                    </tbody>
                </table>
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
                fetch(`/api/supplyInvoice/${invoiceID}`)
                    .then(response => response.json())
                    .then(data => {
                        const modalContent = document.getElementById('modal-content');
                        modalContent.innerHTML = ''; // Clear existing content

                        data.details.forEach(detail => {
                            modalContent.innerHTML += `
                        <tr>
                            <td>${detail.product}</td>
                            <td>${formatRupiah(detail.price)}</td>
                            <td>${detail.Quantity} ${detail.productUnit}</td>
                            <td>${detail.disc}</td>
                            <td>${formatRupiah(detail.total)}</td>
                        </tr>`;
                        });

                        // Set the total amount in the footer
                        document.getElementById('total-amount').innerHTML =
                            `Total Pesanan: ${formatRupiah(data.totalAmount)}`;

                        // Show the modal and overlay
                        document.getElementById('order-modal').classList.add('show');
                        document.querySelector('.overlay').classList.add('show');
                    })
                    .catch(error => {
                        console.error("Error fetching order details:", error);
                    });
            });
        });

        // Show image modal when an image is clicked
        document.querySelectorAll('.invoice_image').forEach(img => {
            img.addEventListener('click', function() {
                const modalImage = document.getElementById('modal-image');
                modalImage.src = this.src; // Set src gambar modal ke gambar yang diklik
                document.getElementById('image-modal').classList.add('show');
                document.getElementById('image-overlay').classList.add('show');
            });
        });

        // Close modal when close button or overlay is clicked
        document.querySelectorAll('.close-button').forEach(button => {
            button.addEventListener('click', () => {
                // Close both modals and overlays
                document.getElementById('order-modal').classList.remove('show');
                document.getElementById('image-modal').classList.remove('show');
                document.querySelectorAll('.overlay').forEach(overlay => overlay.classList.remove('show'));
            });
        });

        // Close modal if overlay is clicked
        document.querySelectorAll('.overlay').forEach(overlay => {
            overlay.addEventListener('click', () => {
                document.getElementById('order-modal').classList.remove('show');
                document.getElementById('image-modal').classList.remove('show');
                overlay.classList.remove('show');
            });
        });
    </script>
</body>

</html>
