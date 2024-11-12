<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>peralatan_kantor</title>
    <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
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
                    <a href="#">Profil</a>
                </div>
                <div class="right">
                    <a href="#">Keranjang <i class="bi bi-cart"></i></a>
                    <a href="#">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="#">Riwayat Pesanan <i class="bi bi-clock-history"></i></a>
                    <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <form action="" method="GET" class="search-container">
            <input type="text" placeholder="Cari produk..." class="search-bar" name="search">
            <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
        </form>

        <h2 class="page-title">Peralatan Kantor</h2>

        <form action="{{ route('customer.addToCart') }}" method="POST" class="product-list">
            @csrf
            @foreach($products as $product)
                <div class="product-item">
                    <img src="{{ asset('images/produk/' . $product->image) }}" alt="{{ $product->ProductName }}">
                    <h3>{{ $product->ProductName }}</h3>
                    <p><strong>Rp{{ number_format($product->pricing->UnitPrice, 0, ',', '.') }}</strong></p>
                    <p>Tersedia: {{ $product->CurrentStock }}</p>

                    <!-- Quantity selector -->
                    <div class="quantity-selector">
                        <i class="bi bi-dash-square decrement" style="display: none;"></i>
                        <input type="number" name="quantity[{{ $product->ProductID }}]" value="0" min="0" max="70" class="quantity-input" style="display: none;" required>
                        <i type="button" class="bi bi-plus-square increment"></i>
                    </div>
                </div>
            @endforeach
            <button type="submit" class="order-button" style="display: none;">Pesan</button>
        </form>
    </div>
    

    <script>
        $(document).ready(function() {
            const togglePesanButton = () => {
                const anyQuantitySelected = $('.quantity-input').toArray().some(input => parseInt($(input).val()) > 0);
                $('.order-button').toggle(anyQuantitySelected);
            };

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

                if (parseInt(input.val()) > 1) {
                    input.val(parseInt(input.val()) - 1);
                } else {
                    input.val(0).hide();
                    $(this).hide();
                }
                togglePesanButton();
            });
        });
    </script>

</body>
</html>