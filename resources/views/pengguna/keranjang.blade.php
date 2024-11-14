<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body>
    <!-- Header Section -->
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
        <nav class="nav">
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
        </nav>
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
                            <a href="{{ route('cart.remove', $item->product->ProductName) }}" class="delete">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </div>
                    @endforeach
                </div>
            </form>
            <form id="opsi" action="{{ route('checkout') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="customerAddress">Opsi Pengiriman:</label>
                    <select id="customerAddress" name="shipping_option" onchange="toggleAddressField()">
                        <option value="ambil_sendiri">Ambil Sendiri</option>
                        <option value="diantar">Diantar</option>
                    </select>
                </div>
                <div id="pengambilan" class="form-group" style="display: none;">
                    <label for="alamat">Alamat Pengiriman:</label>
                    <input type="text" id="alamat" name="alamat">
                </div>
                <label for="pembayaran">Pilih Opsi Pembayaran:</label>
                <select id="pembayaran" name="payment_option" style="margin-bottom:15px; padding:10px">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="kredit">Kredit</option>
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
            <a href="{{ route('pengguna.buat_pesanan') }}" class="back-btn">
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
    </script>
</body>

</html>
