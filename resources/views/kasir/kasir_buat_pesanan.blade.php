<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="{{asset('css/kasir_buat_pesanan.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{asset('images/logo.png')}}" alt="logo">
            <div class="nav">
                <div class="left">
                    <a href="home">Home</a>
                    <a href="#">Profil</a>   
                </div>
                <div class="right">
                    <a href="buat-pesanan">Buat Pesanan <i class="bi bi-cart"></i></a>
                    <a href="#">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="#">Konfirmasi Pesanan <i class="bi bi-clipboard-check"></i></a>
                    <a href="#">Status Pesanan <i class="bi bi-journal-text"></i></a>
                    <a href="#">Keluar <i class="bi bi-box-arrow-right"></i></a>
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


        <form action="{{ route('tambah-pesanan') }}" method="POST" class="product-list">
            <!-- @csrf
            @foreach($products as $product)
                <div class="product-item">
                    <img src="{{ asset('images/produk/' . $product['image']) }}" alt="{{ $product['name'] }}">
                    <h3>{{ $product['name'] }}</h3>
                    <p><strong>Rp{{ number_format($product['price'], 0, ',', '.') }}</strong></p>
                    <p>Tersedia: {{ $product['available'] }} lusin</p>
                    <div class="quantity-selector">
                        <button type="button" class="decrement" style="display: none;">-</button>
                        <input type="number" name="quantity[{{ $product['name'] }}]" value="0" min="0" max="{{ $product['available'] }}" class="quantity-input" style="display: none;">
                        <button type="button" class="increment">+</button>
                    </div>
                </div>
            @endforeach -->

            <div class="product-item">
                <div class="kiri">
                    <img src="{{ asset('images/produk/pensil2b.png') }}" alt="{{ $product['name'] }}">
                </div>
                <div class="tengah">
                    <h2>Pensil Ajaib 2B</h2>
                    <h3><strong>Rp25.000</strong></h3>
                    <h3>Tersedia: 60 lusin</h3>
                    <h3>Kategori: Alat Tulis</h3>
                </div>
                <div class="kanan">
                    <div class="quantity-selector">
                        <i class="bi bi-dash-square decrement" style="display: none;"></i>
                        <input type="number" name="quantity[{{ $product['name'] }}]" value="0" min="0" max="60" class="quantity-input" style="display: none;" required>
                        <i type="button" class="bi bi-plus-square increment"></i>
                    </div>
                </div>
            </div>
            <div class="product-item">
                <div class="kiri">
                    <img src="{{ asset('images/produk/pensil2b.png') }}" alt="{{ $product['name'] }}">
                </div>
                <div class="tengah">
                    <h2>Pensil Ajaib 2B</h2>
                    <h3><strong>Rp25.000</strong></h3>
                    <h3>Tersedia: 60 lusin</h3>
                    <h3>Kategori: Alat Tulis</h3>
                </div>
                <div class="kanan">
                    <div class="quantity-selector">
                        <i class="bi bi-dash-square decrement" style="display: none;"></i>
                        <input type="number" name="quantity[{{ $product['name'] }}]" value="0" min="0" max="70" class="quantity-input" style="display: none;" required>
                        <i type="button" class="bi bi-plus-square increment"></i>
                    </div>
                </div>
            </div>
            <div class="product-item">
                <div class="kiri">
                    <img src="{{ asset('images/produk/pensil2b.png') }}" alt="{{ $product['name'] }}">
                </div>
                <div class="tengah">
                    <h2>Pensil Ajaib 2B</h2>
                    <h3><strong>Rp25.000</strong></h3>
                    <h3>Tersedia: 60 lusin</h3>
                    <h3>Kategori: Alat Tulis</h3>
                </div>
                <div class="kanan">
                    <div class="quantity-selector">
                        <i class="bi bi-dash-square decrement" style="display: none;"></i>
                        <input type="number" name="quantity[{{ $product['name'] }}]" value="0" min="0" max="70" class="quantity-input" style="display: none;" required>
                        <i type="button" class="bi bi-plus-square increment"></i>
                    </div>
                </div>
            </div>
            <div class="product-item">
                <div class="kiri">
                    <img src="{{ asset('images/produk/pensil2b.png') }}" alt="{{ $product['name'] }}">
                </div>
                <div class="tengah">
                    <h2>Pensil Ajaib 2B</h2>
                    <h3><strong>Rp25.000</strong></h3>
                    <h3>Tersedia: 60 lusin</h3>
                    <h3>Kategori: Alat Tulis</h3>
                </div>
                <div class="kanan">
                    <div class="quantity-selector">
                        <i class="bi bi-dash-square decrement" style="display: none;"></i>
                        <input type="number" name="quantity[{{ $product['name'] }}]" value="0" min="0" max="70" class="quantity-input" style="display: none;" required>
                        <i type="button" class="bi bi-plus-square increment"></i>
                    </div>
                </div>
            </div>
            <div class="product-item">
                <div class="kiri">
                    <img src="{{ asset('images/produk/pensil2b.png') }}" alt="{{ $product['name'] }}">
                </div>
                <div class="tengah">
                    <h2>Pensil Ajaib 2B</h2>
                    <h3><strong>Rp25.000</strong></h3>
                    <h3>Tersedia: 60 lusin</h3>
                    <h3>Kategori: Alat Tulis</h3>
                </div>
                <div class="kanan">
                    <div class="quantity-selector">
                        <i class="bi bi-dash-square decrement" style="display: none;"></i>
                        <input type="number" name="quantity[{{ $product['name'] }}]" value="0" min="0" max="70" class="quantity-input" style="display: none;" required>
                        <i type="button" class="bi bi-plus-square increment"></i>
                    </div>
                </div>
            </div>
            <button type="submit" class="order-button" style="display: none;">Pesan</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            function togglePesanButton() {
                // Check if any quantity is greater than 0
                let anyQuantitySelected = false;
                $('.quantity-input').each(function() {
                    if (parseInt($(this).val()) > 0) {
                        anyQuantitySelected = true;
                        return false; // Exit loop early
                    }
                });

                // Show or hide the Pesan button based on the quantity check
                if (anyQuantitySelected) {
                    $('.order-button').show();
                } else {
                    $('.order-button').hide();
                }
            }

            // Increment button functionality
            $('.increment').click(function() {
                let input = $(this).siblings('.quantity-input');
                let decrementButton = $(this).siblings('.decrement');

                // Show input field and decrement button if value is 0
                if (input.val() == 0) {
                    input.val(1);
                    input.show();
                    decrementButton.show();
                } else {
                    let currentValue = parseInt(input.val());
                    let max = parseInt(input.attr('max'));
                    if (currentValue < max) {
                        input.val(currentValue + 1);
                    }
                }

                togglePesanButton(); // Check if we need to show/hide the Pesan button
            });

            // Decrement button functionality
            $('.decrement').click(function() {
                let input = $(this).siblings('.quantity-input');
                let currentValue = parseInt(input.val());

                if (currentValue > 1) {
                    input.val(currentValue - 1);
                } else if (currentValue == 1) {
                    // If the value is 1, set to 0 and hide the input and decrement button
                    input.val(0);
                    input.hide();
                    $(this).hide();
                }

                togglePesanButton(); // Check if we need to show/hide the Pesan button
            });
        });
    </script>
</body>
</html>