<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="{{asset('css/kasir_buat_pesanan.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @livewireStyles
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
                    <a href="stock-barang">Stock Barang <i class="bi bi-box-seam"></i></a>
                    <a href="konfirmasi">Konfirmasi Pesanan <i class="bi bi-clipboard-check"></i></a>
                    <a href="status">Status Pesanan <i class="bi bi-journal-text"></i></a>
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

            <!-- Modal structure -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Produk yang Dipilih</h2>
            <div class="selected-products"></div>
            <h3>Total Harga: <span id="totalPrice">Rp0</span></h3>

            <h2>Informasi Pemesan</h2>
            <label for="customerType">Pilih Tipe Pelanggan:</label>
            <select id="customerType" style="margin-bottom:15px">
                <option value="new">Pelanggan Baru</option>
                <option value="existing">Pelanggan Terdaftar</option>
            </select>

            <!-- Search field for existing customers -->
            <div id="existingCustomerSearch" style="display: none;">
                <div class="form-group">
                    <!-- Pencarian akun pelanggan dengan Livewire -->
                    @livewire('search-customer')
                </div>
            </div>

            <!-- Form for customer information -->
            <form id="orderForm" class="check-out">
                <div class="form-group">
                    <label for="customerName">Nama:</label>
                    <input type="text" id="customerName" name="customerName" required>
                </div>
                <div class="form-group">
                    <label for="customerPhone">Nomor HP:</label>
                    <input type="tel" id="customerPhone" name="customerPhone" required>
                </div>
                <div class="form-group">
                    <label for="customerAddress">Opsi Pengiriman:</label>
                    <select id="customerAddress">
                        <option value="ambil_sendiri">Ambil Sendiri</option>
                        <option value="diantar">Diantar</option>
                    </select>
                </div>
                <div id="pengambilan" class="form-group" style="display: none;">
                    <label for="alamat">Alamat Pengiriman:</label>
                    <input type="text" id="alamat" name="alamat" required>
                </div>
                <label for="pembayaran">Pilih Opsi Pembayaran:</label>
                <select id="pembayaran" style="margin-bottom:15px">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="kredit">Kredit</option>
                </select>
                <button type="button" class="checkout-button">Checkout</button>
            </form>
        </div>
    </div>


    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan atau menyembunyikan field berdasarkan tipe pelanggan yang dipilih
        $('#customerType').change(function() {
            if ($(this).val() === 'existing') {
                $('#existingCustomerSearch').show();
            } else {
                $('#existingCustomerSearch').hide();
                $('#orderForm').find("input[type=text], textarea").val("");
            }
        });

        // Fungsi untuk menampilkan atau menyembunyikan input alamat pengiriman berdasarkan opsi pengiriman
        $('#customerAddress').change(function() {
            if ($(this).val() === 'diantar') {
                $('#pengambilan').show();
            } else {
                $('#pengambilan').hide();
                $('#orderForm').find("input[type=text], textarea").val("");
            }
        });

        // Fungsi untuk menampilkan tombol "Pesan" hanya jika ada produk dengan kuantitas lebih dari 0
        function togglePesanButton() {
            let anyQuantitySelected = false;
            $('.quantity-input').each(function() {
                if (parseInt($(this).val()) > 0) {
                    anyQuantitySelected = true;
                    return false;
                }
            });

            if (anyQuantitySelected) {
                $('.order-button').show();
            } else {
                $('.order-button').hide();
            }
        }

        // Fungsi untuk menambah kuantitas produk ketika tombol "+" ditekan
        $('.increment').click(function() {
            let input = $(this).siblings('.quantity-input');
            let decrementButton = $(this).siblings('.decrement');

            if (input.val() == 0) {
                input.val(1).show();
                decrementButton.show();
            } else {
                let currentValue = parseInt(input.val());
                let max = parseInt(input.attr('max'));
                if (currentValue < max) {
                    input.val(currentValue + 1);
                }
            }
            togglePesanButton();
        });

        // Fungsi untuk mengurangi kuantitas produk ketika tombol "-" ditekan
        $('.decrement').click(function() {
            let input = $(this).siblings('.quantity-input');
            let currentValue = parseInt(input.val());

            if (currentValue > 1) {
                input.val(currentValue - 1);
            } else if (currentValue == 1) {
                input.val(0).hide();
                $(this).hide();
            }
            togglePesanButton();
        });

        // Fungsi untuk memperbarui informasi produk dan total harga di modal
        function updateModal() {
            const selectedProductsContainer = $('.selected-products');
            selectedProductsContainer.empty();
            let totalPrice = 0;

            $('.quantity-input').each(function() {
                let quantity = parseInt($(this).val());
                if (quantity > 0) {
                    let productName = $(this).closest('.product-item').find('h2').text();
                    let priceText = $(this).closest('.product-item').find('strong').text().replace('Rp', '').replace('.', '');
                    let price = parseInt(priceText);
                    let productTotal = price * quantity;
                    totalPrice += productTotal;

                    selectedProductsContainer.append(`<p>${productName} - ${quantity} pcs - Rp${productTotal.toLocaleString('id-ID')}</p>`);
                }
            });

            $('#totalPrice').text(`Rp${totalPrice.toLocaleString('id-ID')}`);
        }

        // Fungsi untuk menampilkan modal dan menghitung total
        $('.order-button').click(function(e) {
            e.preventDefault();
            updateModal();
            $('#orderModal').show();
        });

        // Fungsi untuk menutup modal saat tombol close diklik atau klik di luar modal
        $('.close-button').click(function() {
            $('#orderModal').hide();
        });

        $(window).click(function(event) {
            if ($(event.target).is('#orderModal')) {
                $('#orderModal').hide();
            }
        });

        // Mendengarkan event dari Livewire untuk mengisi data pelanggan yang dipilih
        document.addEventListener('livewire:load', function () {
            Livewire.on('fillCustomerData', customer => {
                $('#customerName').val(customer.name);
                $('#customerPhone').val(customer.phone);
                $('#alamat').val(customer.address);
    console.log(customer)
            });
        });
    });

</script>


    @livewireScripts

</body>
</html>