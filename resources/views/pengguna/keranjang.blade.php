<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
        <nav class="nav">
            <div class="left">
                <a href="{{ route('pengguna.home') }}">Home</a>
                <a href="{{ route('profile.show')}}">Profil</a>
            </div>
            <div class="right">
                <a href="#">Keranjang <i class="bi bi-cart"></i></a>
                <a href="#">Status Pesanan <i class="bi bi-journal-text"></i></a>
                <a href="#">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
            </div>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="container">
        <h1 class="header-title">Keranjang Belanja</h1>
        <div class="header-line"></div>

        <!-- Check if cart is not empty -->
        @if(!empty($cart) && count($cart) > 0)
            <div class="cart-items">
                @foreach($cart as $item)
                    @if($item['quantity'] > 0)
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="{{ asset('images/produk/' . $item['image']) }}" alt="{{ $item['name'] }}">
                        </div>
                        <div class="item-details">
                            <h2>{{ $item['name'] }}</h2>
                            <p>Harga: Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="quantity-control">
                            <button onclick="decrementQuantity('{{ $item['name'] }}')">-</button>
                            <input type="number" name="quantity[{{ $item['name'] }}]" value="{{ $item['quantity'] }}" min="1" max="70" class="quantity-input" required>
                            <button onclick="incrementQuantity('{{ $item['name'] }}')">+</button>
                        </div>
                        <div class="subtotal">Subtotal: Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                        <a href="{{ route('cart.remove', $item['name']) }}" class="delete"><i class="bi bi-trash"></i> Hapus</a>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Footer Section with Shipping, Payment and Total Order -->
<div class="footer">
    <div class="footer-left">
        <div class="shipping-method">
            <label for="shipping_method">Pilih Metode Pengiriman:</label>
            <select name="shipping_method" class="form-control">
                <option value="">Pilih Metode Pengiriman</option>
                <option value="antar">Antar</option>
                <option value="ambil_sendiri">Ambil Sendiri</option>
            </select>
        </div>
        <div class="payment-method">
            <label for="payment_method">Pilih Metode Pembayaran:</label>
            <select name="payment_method" class="form-control">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="kredit">Kredit</option>
            </select>
        </div>
    </div>
                <div class="footer-right">
                    <div class="total-order">
                        <p>Total Pesanan: Rp{{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                    <button class="checkout-btn">Pesan</button>
                </div>
            </div>
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
    </div>

    <!-- JavaScript for Quantity Controls -->
    <script>
        function incrementQuantity(itemName) {
            const input = document.querySelector(`input[name="quantity[${itemName}]"]`);
            if (input) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQuantity(itemName) {
            const input = document.querySelector(`input[name="quantity[${itemName}]"]`);
            if (input && input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</body>
</html>

