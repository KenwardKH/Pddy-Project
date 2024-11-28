<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>peralatan_kantor</title>
    <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('pengguna.home') }}">Home</a>
                    <a href="{{ route('profile.show') }}">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('pengguna.buat_pesanan') }}">Beli Barang <i class="bi bi-bag-plus"></i></a>
                    <a href="/keranjang">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('pengguna.pembayaran') }}">Daftar Pembayaran <i class="bi bi-cash-stack"></i></a>
                    <a href="{{ route('pengguna.status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="{{ route('pengguna.riwayat') }}">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a> 
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <form action="" method="GET" class="search-container">
            <input type="text" placeholder="Cari produk..." class="search-bar" name="search">
            <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
        </form>

        <h2 class="page-title">Peralatan Kantor</h2>

        <form action="{{ route('customer.updateCart') }}" method="POST" class="product-list">
            @csrf
            @foreach ($products as $product)
                <div class="product-item">
                    <img src="{{ asset('images/produk/' . $product->image) }}" alt="{{ $product->ProductName }}">
                    <h3>{{ $product->ProductName }}</h3>

                    <!-- Display product price if available -->
                    <p><strong>Rp{{ number_format($product->pricing->UnitPrice ?? 0, 0, ',', '.') }}/{{ $product->ProductUnit }}</strong>
                    </p>
                    <b>Detail:</b>
                    <p>{{ $product->Description }}</p>
                    <p>Tersedia: {{ $product->CurrentStock }}</p>

                    <!-- Check if the product is in the customer's cart and retrieve the quantity if it is -->
                    @php
                        $cartItem = $cartItems->get($product->ProductID); // Get cart item by product ID, or null if it doesn’t exist
                    @endphp

                    <!-- Quantity selector -->
                    @if ($product->CurrentStock == 0)
                        <p class="habis">Produk Habis</p>
                    @else
                        <div class="quantity-selector">
                            <i class="bi bi-dash-square decrement" style=""></i>
                            <input type="number" name="quantity[{{ $product->ProductID }}]"
                                value="{{ $cartItem ? $cartItem->Quantity : 0 }}" min="0" max="70"
                                class="quantity-input" required>
                            <i type="button" class="bi bi-plus-square increment"></i>
                        </div>
                    @endif
                </div>
            @endforeach
            <button type="submit" class="order-button" style="display: none;">Masukkan ke Keranjang</button>
        </form>
    </div>


    <script>
        $(document).ready(function() {
            // Function to toggle "Masukkan ke Keranjang" button based on input values
            const togglePesanButton = () => {
                const anyQuantitySelected = $('.quantity-input').toArray().some(input => parseInt($(input)
                .val()) > 0);
                $('.order-button').toggle(anyQuantitySelected);
            };

            // Initial check to display button if any input already has a value
            togglePesanButton();

            $('.increment').click(function() {
                const input = $(this).siblings('.quantity-input');
                const decrementButton = $(this).siblings('.decrement');

                if (input.val() == 0) {
                    input.val(1).show();
                    decrementButton.show();
                } else if (parseInt(input.val()) < parseInt(input.attr('max'))) {
                    input.val(parseInt(input.val()) + 1);
                }
                togglePesanButton();
            });

            $('.decrement').click(function() {
                const input = $(this).siblings('.quantity-input');
                const decrementButton = $(this);

                if (parseInt(input.val()) > 1) {
                    input.val(parseInt(input.val()) - 1);
                } else {
                    input.val(0);
                    input.hide(); // Hide input when value is 0
                    decrementButton.hide(); // Hide decrement button when value is 0
                }
                togglePesanButton();
            });

            // Hide inputs and decrement buttons that start at 0 on page load
            $('.quantity-input').each(function() {
                if ($(this).val() == 0) {
                    $(this).hide();
                    $(this).siblings('.decrement').hide();
                }
            });
        });
    </script>


</body>

</html>
