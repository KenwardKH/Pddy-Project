<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_keranjang.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    @livewireStyles
</head>

<body>
    <!-- Header Section -->
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

    <!-- Main Content Section -->
    <div class="container">
        <h1 class="header-title">Keranjang Belanja</h1>
        <div class="header-line"></div>

        @if (!empty($cart) && count($cart) > 0)
            <form action="{{ route('customer.updateCart') }}" method="POST">
                @csrf
                <div class="cart-items">
                    @foreach ($cart as $item)
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="{{ asset('images/produk/' . $item->product->image) }}"
                                    alt="{{ $item->product->ProductName }}">
                            </div>
                            <div class="item-details">
                                <h2>{{ $item->product->ProductName }}</h2>
                                <p>Harga: Rp{{ number_format($item->product->pricing->UnitPrice, 0, ',', '.') }}</p>
                                <p>Tersedia: {{ $item->product->CurrentStock }}</p>
                            </div>
                            <div class="quantity-control">
                                <h4>Jumlah: </h4>
                                <p style="color: #FFA500"><b>{{ $item->Quantity }}</b></p>
                            </div>
                            <div class="subtotal">
                                Subtotal:
                                Rp{{ number_format($item->product->pricing->UnitPrice * $item->Quantity, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('kasir.cart.remove', $item->product->ProductName) }}" class="delete">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </div>
                    @endforeach
                </div>
            </form>
            <form id="opsi" action="{{ route('kasir.checkout') }}" method="POST">
                @csrf
                <h2>Informasi Pemesan</h2>
                <label for="customerType">Pilih Tipe Pelanggan:</label>
                <select id="customerType" name="customerType" style="margin-bottom:15px" onchange="toggleAddressField()">
                    <option value="new">Pelanggan Baru</option>
                    <option value="existing">Pelanggan Terdaftar</option>
                </select>
                <div id="existingCustomerSearch" style="display: none">
                    @livewire('SearchCustomer')
                </div>
                <div id="customerInfo">
                    <div class="form-group">
                        <label for="customerName">Nama:</label>
                        <input type="text" id="customerName" name="newCustomerName">
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Nomor HP:</label>
                        <input type="tel" id="customerPhone" name="newCustomerPhone">
                    </div>
                </div>
                <div class="form-group">
                    <label for="customerAddress">Opsi Pengiriman:</label>
                    <select id="customerAddress" name="shipping_option" onchange="toggleCustomerField()">
                        <option value="pickup">Ambil Sendiri</option>
                        <option value="diantar">Diantar</option>
                    </select>
                </div>
                <div id="pengambilan" class="form-group" style="display: none;">
                    <label for="alamat">Alamat Pengiriman:</label>
                    <input type="text" id="alamat" name="alamat">
                </div>
                <label for="pembayaran">Pilih Opsi Pembayaran:</label>
                <select id="pembayaran" name="payment_option" style="margin-bottom:15px; padding:10px">
                    <option value="tunai">Cash</option>
                    <option value="transfer">Transfer</option>
                </select>
                <div class="footer">
                    <div class="total-order">
                        <h3>Total Pesanan: Rp{{ number_format($total, 0, ',', '.') }}</h3>
                    </div>
                    <button type="submit" class="checkout-btn">Check Out</button>
                </div>
            </form>
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
        <div class="back-button-container">
            <a href="{{ route('buat-pesanan') }}" class="back-btn">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Halaman Pesanan
            </a>
        </div>
    </div>

    <!-- JavaScript for Quantity Controls -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menampilkan atau menyembunyikan input alamat pengiriman berdasarkan opsi pengiriman
            $('#customerAddress').change(function() {
                if ($(this).val() === 'diantar') {
                    $('#pengambilan').show();
                } else {
                    $('#pengambilan').hide();
                    $('#opsi').find("input[type=text], textarea").val("");
                }
            });

            // Show/hide customer search or reset fields based on customer type selection
            $('#customerType').change(function() {
                if ($(this).val() === 'existing') {
                    $('#existingCustomerSearch').slideDown();
                    $('#customerInfo').slideUp();
                } else {
                    $('#existingCustomerSearch').slideUp();
                    $('#customerInfo').slideDown();
                }
            });
        });

        function toggleAddressField() {
            const shippingOption = document.getElementById("customerAddress").value;
            const addressField = document.getElementById("alamat");
            const addressContainer = document.getElementById("pengambilan");

            if (shippingOption === "diantar") {
                addressContainer.style.display = "block";
                addressField.setAttribute("required", "required");
            } else {
                addressContainer.style.display = "none";
                addressField.removeAttribute("required");
                addressField.value = ""; // Clear the field if not required
            }
        }

        function toggleCustomerField() {
            const customerType = document.getElementById("customerType").value;
            const inputNama = document.getElementById("customerName");
            const inputPhone = document.getElementById("customerPhone");

            if (customerType === "new") {
                inputNama.setAttribute("required", "required");
                inputPhone.setAttribute("required", "required");
            } else {
                inputNama.removeAttribute("required");
                inputNama.value = ""; // Clear the field if not required
                inputPhone.removeAttribute("required");
                inputPhone.value = ""; // Clear the field if not required
            }
        }
    </script>
    @livewireScripts
</body>

</html>
