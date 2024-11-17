<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="{{ asset('css/kasir_buat_pesanan.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .modal-product-item img {
            width: 100px
        }
    </style>
    @livewireStyles
</head>

<body>

    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
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


        <form action="{{ route('tambah-pesanan') }}" method="POST" class="product-list">
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
                <div id="existingCustomerSearch" style="display: none">
                    @livewire('SearchCustomer')
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
        document.addEventListener('livewire:load', function() {
            Livewire.on('fillCustomerData', (customer) => {
                $('#customerName').val(customer.name);
                $('#customerPhone').val(customer.phone);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle the visibility of the order button
            const togglePesanButton = () => {
                const anyQuantitySelected = Array.from($('.quantity-input')).some(input => parseInt($(input)
                    .val()) > 0);
                $('.order-button').toggle(anyQuantitySelected);
            };

            // Update modal content
            const updateModal = () => {
                const selectedProductsContainer = $('.selected-products');
                selectedProductsContainer.empty();
                let totalPrice = 0;

                $('.quantity-input').each(function() {
                    const quantity = parseInt($(this).val());
                    if (quantity > 0) {
                        // Retrieve product details dynamically
                        const productItem = $(this).closest('.product-item');
                        const productName = productItem.find('h3').text();
                        const priceText = productItem.find('p strong').text().replace(/[^\d]/g,
                            ''); // Remove non-numeric
                        const unit = productItem.find('p strong').text().split('/').pop()
                            .trim(); // Get the unit from text
                        const imageUrl = productItem.find('img').attr('src'); // Get the image URL
                        const price = parseInt(priceText);
                        const productTotal = price * quantity;
                        totalPrice += productTotal;

                        // Append product details including image
                        selectedProductsContainer.append(`
                <div class="modal-product-item">
                    <img src="${imageUrl}" alt="${productName}" class="modal-product-image">
                    <p>${productName} - ${quantity} ${unit} - Rp${productTotal.toLocaleString('id-ID')}</p>
                </div>
            `);
                    }
                });

                $('#totalPrice').text(`Rp${totalPrice.toLocaleString('id-ID')}`);
            };

            // Update quantity value based on increment or decrement
            const updateQuantity = (input, increment) => {
                let currentValue = parseInt(input.val());
                const max = parseInt(input.attr('max'));
                const min = parseInt(input.attr('min')) || 0;

                if (increment && currentValue < max) {
                    currentValue += 1;
                    input.val(currentValue);
                } else if (!increment && currentValue > min) {
                    currentValue -= 1;
                    input.val(currentValue);
                }

                // Show decrement button and input field if the value is greater than 0
                if (currentValue > 0) {
                    input.show();
                    input.siblings('.decrement').show();
                } else {
                    // Hide decrement button and input field if the value is 0
                    input.hide();
                    input.siblings('.decrement').hide();
                }

                togglePesanButton(); // Update the visibility of the order button
            };


            // Increment button click handler
            $('.increment').click(function() {
                const input = $(this).siblings('.quantity-input');
                updateQuantity(input, true);
            });

            // Decrement button click handler
            $('.decrement').click(function() {
                const input = $(this).siblings('.quantity-input');
                updateQuantity(input, false);
            });

            // Hide decrement button and quantity input if quantity is 0 initially
            $('.quantity-input').each(function() {
                if ($(this).val() == 0) {
                    $(this).hide();
                    $(this).siblings('.decrement').hide();
                }
            });

            // Show or hide order button based on quantity change
            $('.quantity-input').on('change', togglePesanButton);

            // Show modal on order button click
            $('.order-button').click(function(e) {
                e.preventDefault();
                updateModal();
                $('#orderModal').fadeIn();
            });

            // Close modal when close button is clicked
            $('.close-button').click(function() {
                $('#orderModal').fadeOut();
            });

            // Close modal when clicking outside of it
            $(window).click(function(event) {
                if ($(event.target).is('#orderModal')) {
                    $('#orderModal').fadeOut();
                }
            });

            // Show/hide customer search or reset fields based on customer type selection
            $('#customerType').change(function() {
                if ($(this).val() === 'existing') {
                    $('#existingCustomerSearch').slideDown();
                } else {
                    $('#existingCustomerSearch').slideUp();
                    $('#orderForm').find("input[type=text], textarea").val("");
                }
            });

            // Show/hide address input based on delivery option
            $('#customerAddress').change(function() {
                if ($(this).val() === 'diantar') {
                    $('#pengambilan').slideDown();
                } else {
                    $('#pengambilan').slideUp();
                    $('#pengambilan').find("input[type=text]").val("");
                }
            });

            // Livewire integration for filling customer data
            document.addEventListener('livewire:load', function() {
                Livewire.on('fillCustomerData', (customer) => {
                    $('#customerName').val(customer.name);
                    $('#customerPhone').val(customer.phone);
                    $('#alamat').val(customer.address);
                });
            });
        });
    </script>


    @livewireScripts
</body>

</html>
