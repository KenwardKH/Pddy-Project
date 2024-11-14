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
                    <a href="{{ route('pengguna.home') }}">Home</a>
                    <a href="{{ route('profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('pengguna.buat_pesanan') }}">Beli Barang <i class="bi bi-bag-plus"></i></a>
                    <a href="/keranjang">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('pengguna.status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('pengguna.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                    <a href="{{ route('logout') }}">Keluar <i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="dashboard">
            <form action="" method="GET" class="search-container">
                <input type="text" placeholder="Cari produk..." class="search-bar" name="search">
                <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
            </form>
            <h1>Riwayat Pesanan</h1>

        </div>
        <div class="table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Detail</th>
                        <th>Opsi Pengantaran</th>
                        <th>Alamat</th>
                        <th>Jumlah Produk</th>
                        <th>Total Harga</th>
                        <th>Opsi Pembayaran</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Cetak Invoice</th>
                        <th>Tanggal Pesan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->InvoiceID }}</td>
                            <td><button class="cetak-button">Detail</button></td>
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
                            <td>{{ $invoice->invoiceDetails->sum('Quantity') }}</td>
                            <td>{{ $invoice->transactionLog->TotalAmount ?? 'N/A' }}</td>
                            <td>{{ $invoice->payment_option ?? 'N/A' }}</td>
                            <td>
                                @if($invoice->DueDate == null)
                                N/A
                                @else
                                {{$invoice->DueDate}}
                                @endif
                            </td>
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
                            <td>{{ $invoice->InvoiceDate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
</body>

</html>
