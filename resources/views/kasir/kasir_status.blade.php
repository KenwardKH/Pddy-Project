<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kasir Home</title>
        <link rel="stylesheet" href="{{asset('css/kasir_status.css')}}">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="{{asset('images/logo.png')}}" alt="logo">
                <div class="nav">
                    <div class="left">
                        <a href="{{ route('kasir.home') }}">Home</a>
                        <a href="#">Profil</a>   
                    </div>
                    <div class="right">
                        <a href="buat-pesanan">Buat Pesanan <i class="bi bi-cart"></i></a>
                        <a href="{{ route('kasir.stock') }}">Stock Barang <i class="bi bi-box-seam"></i></a>
                        <a href="{{ route('kasir.konfirmasi') }}">Konfirmasi Pesanan <i class="bi bi-clipboard-check"></i></a>
                        <a href="{{ route('status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                        <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="dashboard">
                <form action="" method="GET" class="search-container">
                    <input type="text" placeholder="Cari produk..." class="search-bar" name="search">
                    <select class="category-dropdown" name="category">
                        <option value="">Semua Kategori</option>
                        <option value="kategori1">Kategori 1</option>
                        <option value="kategori2">Kategori 2</option>
                        <option value="kategori3">Kategori 3</option>
                    </select>
                    <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
                </form>
                <h1>Status Pesanan</h1>

                <!-- Toggle Buttons -->
                <div class="button-container">
                <a href="{{ route('status', 'diantar') }}" class="toggle-button  {{ $type === 'diantar' ? 'active' : '' }}">Pesanan Diantar</a>
                <a href="{{ route('status', 'ambil') }}" class="toggle-button  {{ $type === 'ambil' ? 'active' : '' }}">Ambil Sendiri</a>
            </div>
            <div class="table">
                @if ($type === 'diantar')
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor Telepon</th>
                                <th>Detail</th>
                                <th>Total Harga</th>
                                <th>Alamat Pengiriman</th>
                                <th>Pembayaran</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Cetak Invoice</th>
                                <th>Tanggal Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order['no'] }}</td>
                                    <td>{{ $order['nama'] }}</td>
                                    <td>{{ $order['nomor_hp'] }}</td>
                                    <td><a href="#" class="detail-link">Detail</a></td>
                                    <td>Rp{{ number_format($order['total'], 0, ',', '.') }}</td>
                                    <td>{{ $order['alamat'] }}</td>
                                    <td>{{ $order['pembayaran'] }}</td>
                                    <td>{{ $order['tempo'] }}</td>
                                    <td>
                                        @if ($order['status'] === 'Diproses')
                                            <a href="{{ route('status.update', ['id' => $order['no'], 'type' => 'diantar']) }}" class="btn-status">Diproses</a>
                                        @elseif ($order['status'] === 'Diantar')
                                            <a href="{{ route('status.update', ['id' => $order['no'], 'type' => 'diantar']) }}" class="btn-status">Diantar</a>
                                        @else
                                            <span class="status-complete">Selesai</span>
                                        @endif
                                    </td>
                                    <td><button class="cetak-button">Cetak</button></td>
                                    <td>{{ $order['tanggal'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif ($type === 'ambil')
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor Telepon</th>
                                <th>Detail</th>
                                <th>Total Harga</th>
                                <th>Alamat Pengiriman</th>
                                <th>Pembayaran</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Cetak Invoice</th>
                                <th>Tanggal Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order['no'] }}</td>
                                    <td>{{ $order['nama'] }}</td>
                                    <td>{{ $order['nomor_hp'] }}</td>
                                    <td><a href="#" class="detail-link">Detail</a></td>
                                    <td>Rp{{ number_format($order['total'], 0, ',', '.') }}</td>
                                    <td>{{ $order['alamat'] }}</td>
                                    <td>{{ $order['pembayaran'] }}</td>
                                    <td>{{ $order['tempo'] ?? '-' }}</td>
                                    <td>
                                        @if ($order['status'] === 'Diproses')
                                            <a href="{{ route('status.update', ['id' => $order['no'], 'type' => 'ambil']) }}" class="btn-status">Diproses</a>
                                        @elseif ($order['status'] === 'Menunggu Pengambilan')
                                            <a href="{{ route('status.update', ['id' => $order['no'], 'type' => 'ambil']) }}" class="btn-status">Menunggu Pengambilan</a>
                                        @else
                                            <span class="status-complete">Selesai</span>
                                        @endif
                                    </td>
                                    <td><button class="cetak-button">Cetak</button></td>
                                    <td>{{ $order['tanggal'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</body>
</html>