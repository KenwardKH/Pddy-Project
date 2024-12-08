<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Supply</title>
    <link rel="stylesheet" href="{{ asset('css/owner_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner_tambahSupply.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
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
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: 0">
                        @csrf
                        <button type="submit" class="btn-link"
                            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="main">
            <h2>Tambah Stock Barang</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Pesan Sukses -->
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('supplyInvoice.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="SupplierID">Nama Supplier</label>
                    <select name="SupplierID" id="SupplierID" class="form-control" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->SupplierID }}">{{ $supplier->SupplierName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="SupplyDate">Tanggal Supply</label>
                    <input type="date" name="SupplyDate" id="SupplyDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="SupplyInvoiceNumber">Nomor Invoice</label>
                    <input type="text" name="SupplyInvoiceNumber" id="SupplyInvoiceNumber" class="form-control">
                </div>
                <div class="form-group">
                    <label for="SupplyInvoiceImage">Unggah Gambar Invoice</label>
                    <input type="file" name="SupplyInvoiceImage" id="SupplyInvoiceImage" class="form-control" required accept="image/jpeg, image/png, image/jpg">
                </div>
                <h4>Produk</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Beli</th>
                            <th>Subtotal</th> <!-- Tambahkan kolom untuk subtotal -->
                            <th>Diskon</th>
                            <th>Total (setelah diskon)</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="products-table">
                        <tr>
                            <td>
                                <select name="ProductID[]" class="form-control" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->ProductID }}">{{ $product->ProductName }}
                                            ({{ $product->ProductUnit }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="form-group-input">
                                    <input type="number" name="Quantity[]" class="form-control quantity" required>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-input">
                                    <input type="number" step="0.01" name="SupplyPrice[]"
                                        class="form-control supply-price" required>
                                </div>
                            </td>
                            <td class="subtotal">0.00</td>
                            <td>
                                <div class="form-group-input">
                                    <input type="text" name="Discount[]" class="form-control discount"
                                        placeholder="10+5" required>
                                </div>
                            </td>
                            <td class="final-price">0.00</td>
                            <td>
                                <button type="button" class="btn btn-danger remove-product">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Tambahkan elemen untuk menampilkan Total -->
                <h4 class="total">
                    <div>Total: Rp <span id="total-amount">0.00</span></div>
                    <button type="button" id="add-product" class="btn btn-secondary">+ Tambah Produk</button>
                </h4>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>

    </div>
    <script>
        function parseDiscount(discountStr) {
            // Split berdasarkan tanda '+' untuk mendapatkan beberapa diskon
            const discounts = discountStr.split('+').map(val => parseFloat(val.trim()) || 0);

            let discountedPrice = 1; // Mulai dari 100% (1)
            discounts.forEach(discount => {
                discountedPrice *= (1 - discount / 100); // Terapkan setiap diskon secara bertahap
            });

            return discountedPrice;
        }


        // Fungsi untuk menghitung subtotal
        function calculateSubtotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const supplyPrice = parseFloat(row.querySelector('.supply-price').value) || 0;
            const subtotal = quantity * supplyPrice;

            row.querySelector('.subtotal').textContent = subtotal.toFixed(2); // Harga sebelum diskon
            return subtotal;
        }

        // Fungsi untuk menghitung harga setelah diskon
        function calculateFinalPrice(row) {
            const subtotal = parseFloat(row.querySelector('.subtotal').textContent) || 0;
            const discountFactor = parseDiscount(row.querySelector('.discount').value || '0'); // Faktor diskon bertahap
            const finalPrice = subtotal * discountFactor; // Harga setelah diskon bertahap

            row.querySelector('.final-price').textContent = finalPrice.toFixed(2); // Harga setelah diskon
            return finalPrice;
        }

        // Fungsi untuk menghitung total semua produk
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('#products-table tr').forEach(row => {
                calculateSubtotal(row); // Pastikan subtotal dihitung sebelum final price
                total += calculateFinalPrice(row); // Hitung total berdasarkan harga setelah diskon
            });
            document.getElementById('total-amount').textContent = total.toFixed(2); // Perbarui total
        }

        // Tambahkan event listener untuk perubahan jumlah atau harga beli
        document.getElementById('products-table').addEventListener('input', function(event) {
            if (event.target.classList.contains('quantity') || event.target.classList.contains('supply-price') ||
                event.target.classList.contains('discount')) {
                calculateTotal(); // Hitung ulang total ketika input berubah
            }
        });


        // Fungsi untuk menambahkan baris produk baru
        document.getElementById('add-product').addEventListener('click', function() {
            const tableBody = document.getElementById('products-table');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
        <td>
            <select name="ProductID[]" class="form-control" required>
                <option value="">Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->ProductID }}">{{ $product->ProductName }} ({{ $product->ProductUnit }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <div class="form-group-input">
                <input type="number" name="Quantity[]" class="form-control quantity" required>
            </div>
        </td>
        <td>
            <div class="form-group-input">
                <input type="number" step="0.01" name="SupplyPrice[]" class="form-control supply-price" required>
            </div>
        </td>
        <td class="subtotal">0.00</td>
        <td>
            <input type="text" name="Discount[]" class="form-control discount" placeholder="10+5" required>
        </td>
        <td class="final-price">0.00</td>
        <td>
            <button type="button" class="btn btn-danger remove-product">Hapus</button>
        </td>
    `;

            // Tambahkan event listener untuk tombol hapus
            newRow.querySelector('.remove-product').addEventListener('click', function() {
                newRow.remove();
                calculateTotal(); // Hitung ulang total setelah baris dihapus
            });

            // Tambahkan baris ke tabel
            tableBody.appendChild(newRow);
        });

        // Tambahkan event listener untuk tombol hapus pada baris awal
        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('tr').remove();
                calculateTotal(); // Hitung ulang total setelah baris dihapus
            });
        });

        // Hitung total awal
        calculateTotal();
    </script>
</body>

</html>
