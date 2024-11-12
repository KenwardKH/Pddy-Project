<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
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
                                <button type="button"
                                    onclick="decrementQuantity('{{ $item->product->ProductID }}')">-</button>
                                <input type="number" name="quantity[{{ $item->product->ProductID }}]"
                                    value="{{ $item->Quantity }}" min="0"
                                    max={{ $item->product->CurrentStock }} class="quantity-input" required>
                                <button type="button"
                                    onclick="incrementQuantity('{{ $item->product->ProductID }}')">+</button>
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

                <div class="footer">
                    <div class="total-order">
                        <p>Total Pesanan: Rp{{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                    <button type="submit" class="checkout-btn">Check Out</button>
                </div>
            </form>
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
        <div class="back-button-container">
            <a href="{{ route('pengguna.peralatan_kantor.index') }}" class="back-btn">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Peralatan Kantor
            </a>
        </div>
    </div>

    <!-- JavaScript for Quantity Controls -->
    <script>
        function incrementQuantity(productID) {
            const input = document.querySelector(`input[name="quantity[${productID}]"]`);
            if (input) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQuantity(productID) {
            const input = document.querySelector(`input[name="quantity[${productID}]"]`);
            if (input && input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</body>

</html>
