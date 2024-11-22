<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Home</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_status.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            <form action="" method="GET" class="search-container">
                <input type="text" placeholder="Cari produk..." class="search-bar" name="search">
                <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
            </form>
            <h1>Status Pesanan</h1>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Toggle Buttons -->
            <div class="button-container">
                <a href="{{ route('status', 'delivery') }}"
                    class="toggle-button  {{ $type === 'delivery' ? 'active' : '' }}">Pesanan Diantar</a>
                <a href="{{ route('status', 'pickup') }}"
                    class="toggle-button  {{ $type === 'pickup' ? 'active' : '' }}">Ambil Sendiri</a>
            </div>
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
                            <th>Jatuh Tempo</th>
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
                                    <td>{{ $invoice->totalAmount ?? 'N/A' }}</td>
                                    <td>{{ $invoice->deliveryStatus->alamat ?? 'N/A' }}</td>
                                    <td>{{ $invoice->payment_option ?? 'N/A' }}</td>
                                    <td>{{ $invoice->DueDate ?? 'N/A' }}</td>
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
                                        <form action="{{ route('pesanan.destroy', $invoice->InvoiceID) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-button">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @elseif ($invoice->type === 'pickup')
                                <tr>
                                    <td>{{ $invoice->InvoiceID }}</td>
                                    <td>{{ $invoice->customerName }}</td>
                                    <td>{{ $invoice->customerContact }}</td>
                                    <td>{{ $invoice->invoiceDetails->sum('Quantity') }}</td>
                                    <td>{{ $invoice->totalAmount ?? 'N/A' }}</td>
                                    <td>{{ $invoice->payment_option ?? 'N/A' }}</td>
                                    <td>{{ $invoice->DueDate ?? 'N/A' }}</td>
                                    <td>
                                        <button class="detail-button"
                                            data-id="{{ $invoice->InvoiceID }}">Detail</button>
                                    </td>
                                    <td>
                                        <form action="{{ route('status.next', $invoice->InvoiceID) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $invoice->type }}">
                                            <button type="submit" class="status-button">
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
                                        <form action="{{ route('pesanan.destroy', $invoice->InvoiceID) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-button">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
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
