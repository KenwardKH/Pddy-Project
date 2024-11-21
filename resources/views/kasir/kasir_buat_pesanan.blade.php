<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_buat_pesanan.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            <div class="nav">
                <div class="left">
                    <a href="{{ route('kasir.home') }}">Home</a>
                    <a href="#">Profil</a>
                </div>
                <div class="right">
                    <a href="{{ route('buat-pesanan') }}">Buat Pesanan <i class="bi bi-bag-plus"></i></a>
                    <a href="{{ route('kasir.cart') }}">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="{{ route('kasir.stock') }}">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="{{ route('status') }}">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="#">Riwayat Pesanan <i class="bi bi-journal-text"></i></a>
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


        <form action="{{ route('kasir.updateCart') }}" method="POST" class="product-list">
            @csrf
            @foreach ($products as $product)
                <div class="product-item">
                    <img src="{{ asset('images/produk/' . $product->image) }}" alt="{{ $product->ProductName }}">
                    <h3>{{ $product->ProductName }}</h3>

                    <!-- Display product price if available -->
                    <p><strong>Rp{{ number_format($product->pricing->UnitPrice ?? 0, 0, ',', '.') }}/{{ $product->unit }}</strong>
                    </p>
                    <b>Detail:</b>
                    <p>{{ $product->Description }}</p>
                    <p>Tersedia: {{ $product->CurrentStock }}</p>

                    <!-- Check if the product is in the customer's cart and retrieve the quantity if it is -->
                    @php
                        // Mendapatkan item keranjang dari collection berdasarkan ProductID
                        $cartItem = $cartItems->get($product->ProductID);
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
            <button type="submit" class="order-button" style="display: none;">Pesan</button>
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
