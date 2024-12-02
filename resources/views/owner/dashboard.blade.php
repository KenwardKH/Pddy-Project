<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_owner.css') }}">
    <style>
        .produk-populer {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8ffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 60%;
            margin: 20px auto;
        }
    
        .produk-populer h2 {
            margin-bottom: 20px;
            color: #333;
        }
    
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    
        .styled-table th, .styled-table td {
            text-align: center;
            padding: 12px 15px;
        }
    
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: center;
        }
    
        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }
    
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
    
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="item">
                <button class="btn">
                    <i class="bi bi-box-seam"></i>
                    <span>Produk<br>{{ $produkCount }}</span>
                </button>
            </div>
            <div class="item">
                <button class="btn">
                    <i class="bi bi-person"></i>
                    <span>Kasir<br>{{ $kasirCount }}</span>
                </button>
            </div>
            <div class="item">
                <button class="btn">
                    <i class="bi bi-person"></i>
                    <span>Pembeli<br>{{ $pembeliCount }}</span>
                </button>
            </div>
            <div class="item">
                <button class="btn">
                    <i class="bi bi-shop"></i>
                    <span>Suplier<br>{{ $suplierCount }}</span>
                </button>
            </div>
        </div>

        <div class="produk-populer">
            <h2>Produk Populer</h2>
            <div class="produk-list">
                <div class="produk-item">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Produk</th>
                                <th>Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($popular_products as $popular_product)
                                <tr>
                                    <td>{{$popular_product->month}}</td>
                                    <td>{{$popular_product->productName}}</td>
                                    <td>{{$popular_product->sold}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Laporan Penjualan -->
        <div class="laporan">
            <h2>Transaksi Bulanan</h2>
            <div class="lap">
                <div class="laporan-item">
                    <canvas id="transactionsChart" width="400" height="200"></canvas>
                </div>

            </div>
        </div>

    </div>
    <script>
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        const transactionsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($reports->pluck('ReportMonth')) !!}, // X-axis (months)
                datasets: [
                    {
                        label: 'Total Transactions',
                        data: {!! json_encode($reports->pluck('TotalTransactions')) !!}, // Y-axis (number of transactions)
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4, // Curve for the line
                        fill: true,
                        borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Transactions',
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Report Month',
                        },
                    },
                },
            },
        });
    </script>
</body>

</html>
