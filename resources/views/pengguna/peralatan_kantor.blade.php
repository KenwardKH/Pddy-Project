<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .quantity-selector {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
        }

        .increment,
        .decrement {
            font-size: 24px;
            cursor: pointer;
        }

        button.open-modal {
            background-color: #4CAF50;
            /* Warna hijau */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.button-edit {
            background-color: yellow;
            color: black
        }

        button.open-modal:hover {
            background-color: #45a049;
            /* Warna hijau lebih gelap */
            transform: scale(1.05);
            /* Efek zoom */
        }

        button.button-edit:hover {
            background-color: rgba(255, 255, 0, 0.721);
            transform: scale(1.05);
        }

        button.open-modal:disabled {
            background-color: #cccccc;
            /* Warna abu-abu */
            cursor: not-allowed;
        }

        /* Gaya untuk tombol Tambahkan ke Keranjang */
        #add-to-cart {
            background-color: #007BFF;
            /* Warna biru */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        #add-to-cart:hover {
            background-color: #0056b3;
            /* Warna biru lebih gelap */
            transform: scale(1.05);
            /* Efek zoom */
        }

        .not-found-message {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #ffe6e6;
            border: 1px solid #ff4d4d;
            border-radius: 8px;
            color: #ff1a1a;
        }
    </style>
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
                        <button type="submit" class="btn btn-link"
                            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                            <a>Keluar <i class="bi bi-box-arrow-right"></i></a>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <form action="{{ route('pengguna.buat_pesanan') }}" method="GET" class="search-container">
            <input type="text" placeholder="Cari produk..." class="search-bar" name="search"
                value="{{ request('search') }}">
            <button type="submit" class="search-button"><i class="bi bi-search"></i></button>
        </form>


        <h2 class="page-title">Beli Produk</h2>

        <div class="product-list">
            @csrf
            @if ($products->isEmpty())
                <!-- Pesan jika barang tidak ditemukan -->
                    <h3 class="not-found-message">Barang tidak ditemukan</h3>
            @else
                @foreach ($products as $product)
                    <div class="product-item">
                        <img src="{{ asset('images/produk/' . $product->image) }}" alt="{{ $product->ProductName }}">
                        <h3>{{ $product->ProductName }}</h3>
                        <p>
                            <strong>Rp{{ number_format($product->pricing->UnitPrice ?? 0, 0, ',', '.') }}</strong> /
                            <span
                                style="color: #ff5722; font-weight: bold; background-color: #ffe6cc; padding: 2px 6px; border-radius: 4px;">
                                {{ $product->ProductUnit }}
                            </span>
                        </p>
                        <p>{{ $product->Description }}</p>
                        <p>Tersedia: {{ $product->CurrentStock }}</p>

                        @php
                            $cartItem = $cartItems->get($product->ProductID); // Cek apakah produk ada di keranjang
                        @endphp

                        @if ($product->CurrentStock == 0)
                            <p class="habis">Produk Habis</p>
                        @else
                            <div>
                                <!-- Menampilkan quantity yang ada di keranjang jika produk ada di keranjang -->
                                @if ($cartItem)
                                    <p>Jumlah di Keranjang: {{ $cartItem->Quantity }}</p>
                                @endif
                                <button type="button"
                                    class="open-modal {{ $cartItem && $cartItem->Quantity > 0 ? 'button-edit' : '' }}"
                                    data-id="{{ $product->ProductID }}" data-name="{{ $product->ProductName }}"
                                    data-stock="{{ $product->CurrentStock }}"
                                    data-quantity="{{ $cartItem ? $cartItem->Quantity : 0 }}">
                                    @if ($cartItem)
                                        @if ($cartItem->Quantity == 0)
                                            Pesan
                                        @else
                                            Edit
                                        @endif
                                    @else
                                        Pesan
                                    @endif
                                </button>

                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
            <a href="/keranjang">
                <button class="order-button" style="display: none;" id="go-to-cart-btn">
                    Pergi keKeranjang
                </button>
            </a>
            </form>
        </div>

        <!-- Modal -->
        <div id="product-modal" class="modal">
            <form action="{{ route('customer.updateCart') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="product_id" id="modal-product-id">
                <input type="hidden" id="modal-quantity-name" value="">
                <span class="close">&times;</span>
                <h3 id="modal-product-name"></h3>
                <p>Stok: <span id="modal-product-stock"></span></p>
                <div class="quantity-selector">
                    <i class="bi bi-dash-square decrement"></i>
                    <input type="number" id="modal-quantity" value="{{ isset($cartItem) && $cartItem ? $cartItem->Quantity : 0 }}"
                        min="0" class="quantity-input" required>
                    <i class="bi bi-plus-square increment"></i>
                </div>
                <button type="submit" id="add-to-cart">Tambahkan/Edit ke Keranjang</button>
            </form>

        </div>

        <script>
            $(document).ready(function() {
                const modal = $("#product-modal");
                const modalProductId = $("#modal-product-id");
                const modalProductName = $("#modal-product-name");
                const modalProductStock = $("#modal-product-stock");
                const modalQuantity = $("#modal-quantity");
                let currentProductId;

                // Menampilkan modal ketika tombol "Pesan" diklik
                $(".open-modal").click(function() {
                    currentProductId = $(this).data("id");
                    const productName = $(this).data("name");
                    const productStock = $(this).data("stock");
                    const productQuantity = $(this).data("quantity");

                    // Atur nilai dalam modal
                    modalProductId.val(currentProductId);
                    modalProductName.text(productName);
                    modalProductStock.text(productStock);
                    modalQuantity.attr("max", productStock).val(productQuantity);

                    // Menambahkan name dinamis untuk quantity sesuai dengan ProductID
                    const dynamicName = `quantity[${currentProductId}]`;
                    $("#modal-quantity").attr("name", dynamicName);

                    modal.show();
                });

                // Menutup modal ketika tombol "X" diklik
                $(".close").click(function() {
                    modal.hide();
                });

                // Menambahkan logika untuk mengubah nilai quantity dengan tombol + dan -
                $(".increment").click(function() {
                    const max = parseInt(modalQuantity.attr("max"));
                    let value = parseInt(modalQuantity.val());
                    if (value < max) modalQuantity.val(value + 1);
                });

                $(".decrement").click(function() {
                    let value = parseInt(modalQuantity.val());
                    if (value > 0) modalQuantity.val(value - 1);
                });

                // Fungsi untuk menampilkan atau menyembunyikan tombol "Pergi ke Keranjang"
                const togglePesanButton = () => {
                    // Memeriksa apakah ada produk dengan jumlah di keranjang lebih dari 0
                    const anyQuantityInCart = $(".product-item").toArray().some(product => {
                        const quantityText = $(product).find("p:contains('Jumlah di Keranjang:')").text();
                        const quantity = parseInt(quantityText.replace("Jumlah di Keranjang: ", ""));
                        return quantity > 0; // Jika ada produk dengan quantity lebih dari 0, return true
                    });

                    if (anyQuantityInCart) {
                        $("#go-to-cart-btn").show(); // Menampilkan tombol jika ada produk dengan quantity > 0
                    } else {
                        $("#go-to-cart-btn")
                            .hide(); // Menyembunyikan tombol jika tidak ada produk dengan quantity > 0
                    }
                };

                // Mengupdate nilai quantity ke keranjang dan menyembunyikan modal
                $("#add-to-cart").click(function() {
                    const quantity = modalQuantity.val();
                    $(`input[name='quantity[${currentProductId}]']`).val(quantity);
                    modal.hide();
                    togglePesanButton
                        (); // Memperbarui tampilan tombol "Pergi ke Keranjang" setelah menambahkannya ke keranjang
                });

                // Inisialisasi tampilan tombol saat halaman dimuat
                togglePesanButton(); // Memanggil fungsi ini pada saat halaman dimuat untuk memeriksa status keranjang

                // Menambahkan event listener untuk perubahan quantity (jika ada perubahan jumlah)
                $(".quantity-input").on("input", function() {
                    togglePesanButton(); // Memperbarui tampilan tombol setiap kali quantity berubah
                });
            });
        </script>
</body>

</html>
