<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('front') }}/css/bootstrap.min.css" />

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('front') }}/css/slick.css" />
    <link type="text/css" rel="stylesheet" href="{{ asset('front') }}/css/slick-theme.css" />

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('front') }}/css/nouislider.min.css" />

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('front') }}/css/font-awesome.min.css">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('front') }}/css/style.css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <style>
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #4CAF50;
            color: white;
            border-radius: 4px;
            z-index: 1000;
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s;
        }

        .toast.error {
            background: #F44336;
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #D10024;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Quick View Modal */
        #quickViewModal .modal-dialog {
            max-width: 900px;
        }

        #quickViewModal .modal-body {
            padding: 30px;
        }

        #quickViewModal .close {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 30px;
            z-index: 1;
        }

        .product-gallery {
            margin-bottom: 20px;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .gallery-thumbs {
            display: flex;
            gap: 10px;
        }

        .gallery-thumbs img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            cursor: pointer;
            border: 1px solid #ddd;
        }

        .gallery-thumbs img:hover {
            border-color: #D10024;
        }

        .product-price {
            font-size: 24px;
            color: #D10024;
            margin: 15px 0;
        }

        .product-price del {
            font-size: 18px;
            color: #999;
            margin-left: 10px;
        }

        .discount {
            background: #D10024;
            color: white;
            padding: 3px 8px;
            font-size: 14px;
            border-radius: 3px;
            margin-left: 10px;
        }

        .product-actions {
            margin: 20px 0;
            display: flex;
            gap: 10px;
        }

        .product-actions .btn {
            padding: 10px 20px;
        }

        /* Cart Dropdown Scrollbar */
        /* .cart-dropdown {
        max-height: 400px;
        overflow-y: auto;
        width: 300px; Sesuaikan dengan lebar dropdown
    } */
        /* Force scrollbar */
        /* .cart-list {
        overflow-y: scroll !important;
        max-height: 300px;
        display: block;
    } */

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination>li>a,
        .pagination>li>span {
            color: #D10024;
            border: 1px solid #ddd;
            padding: 8px 15px;
            margin: 0 2px;
        }

        .pagination>li>a:hover {
            background: #f5f5f5;
        }

        .pagination>.active>span {
            background-color: #D10024;
            border-color: #D10024;
            color: white;
        }

        .pagination>.disabled>span {
            color: #777;
        }

        #searchAddressModal {
            z-index: 1060 !important;
        }

        .modal-backdrop+.modal-backdrop {
            z-index: 1050 !important;
        }

        /* Timeline */
        .timeline {
            list-style: none;
            padding: 0;
            position: relative;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #eee;
            left: 5px;
            margin-left: -1.5px;
        }

        .timeline>li {
            position: relative;
            margin-bottom: 15px;
            padding-left: 20px;
        }

        .timeline>li:before {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #D10024;
            left: 0;
            top: 5px;
        }

        /* Badge Status */
        .badge-menunggu_pembayaran {
            background-color: #ffc107;
            color: #000;
        }

        .badge-diproses {
            background-color: #17a2b8;
        }

        .badge-dikirim {
            background-color: #007bff;
        }

        .badge-selesai {
            background-color: #28a745;
        }

        .badge-dibatalkan {
            background-color: #dc3545;
        }

        /* resources/css/front.css */
        .card-payment {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .payment-icon {
            margin-bottom: 20px;
        }

        .btn-payment-action {
            min-width: 200px;
            padding: 10px 20px;
        }

        /* Modal Pencarian Alamat */
        #searchAddressModal .modal-dialog {
            max-width: 800px;
        }

        #searchAddressModal .modal-content {
            border-radius: 10px;
            overflow: hidden;
        }

        #searchAddressModal .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }

        #searchAddressModal .modal-title {
            font-weight: 600;
            color: #333;
        }

        #searchAddressModal .modal-body {
            padding: 20px;
        }

        #searchAddressModal .table {
            margin-bottom: 0;
        }

        #searchAddressModal .table th {
            border-top: none;
            font-weight: 500;
        }

        #searchAddressModal .select-address {
            white-space: nowrap;
        }

        .modal-backdrop {
            z-index: 1040;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }
    </style>

    @stack('style')

</head>

<body>
    <!-- HEADER -->
    <header>
        <!-- TOP HEADER -->
        <div id="top-header">
            <div class="container">
                <ul class="header-links pull-left">
                    <li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i> pesonacom@gmail.com</a></li>
                    <li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
                </ul>
                <ul class="header-links pull-right">
                    {{-- <li><a href="#"><i class="fa fa-dollar"></i> IDR</a></li> --}}
                    @auth()
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user-o"></i> My Account <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('profile.index') }}" class="btn btn-link"
                                        style="text-decoration: none; color: inherit;">Profile</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" class="btn btn-link"
                                        style="text-decoration: none; color: inherit;"
                                        onclick="event.preventDefault(); document.getElementById('form-logout').submit();">Logout</a>
                                    <form action="{{ route('logout') }}" method="POST" style="display: inline;"
                                        id="form-logout">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}"><i class="fa fa-user-o"></i> Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <!-- /TOP HEADER -->

        <!-- MAIN HEADER -->
        <div id="header">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <!-- LOGO -->
                    <div class="col-md-4">
                        <div class="header-logo">
                            <a href="/" class="logo">
                                <img src="{{ asset('front/logo.png') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <!-- /LOGO -->

                    <!-- SEARCH BAR -->
                    <div class="col-md-4">
                        {{-- <div class="header-search">
                            <form>
                                <select class="input-select">
                                    <option value="0">All Categories</option>
                                    <option value="1">Category 01</option>
                                    <option value="1">Category 02</option>
                                </select>
                                <input class="input" placeholder="Search here">
                                <button class="search-btn">Search</button>
                            </form>
                        </div> --}}
                    </div>
                    <!-- /SEARCH BAR -->

                    <!-- ACCOUNT -->
                    <div class="col-md-4 clearfix">
                        <div class="header-ctn">
                            <!-- Wishlist -->
                            <div>
                                <a href="{{ route('pelanggan.pesanan.index') }}">
                                    <i class="fa fa-file-text-o"></i>
                                    <span>Pesanan Saya</span>
                                    <div class="qty pesanan-qty">
                                        {{ auth()->check() ? auth()->user()->pesanan()->count() : 0 }}</div>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('pelanggan.wishlist.index') }}">
                                    <i class="fa fa-heart-o"></i>
                                    <span>Your Wishlist</span>
                                    <div class="qty wishlist-qty">
                                        {{ auth()->check() ? auth()->user()->wishlists()->count() : 0 }}</div>
                                </a>
                            </div>
                            <!-- /Wishlist -->

                            <!-- Cart -->
                            {{-- @if (!request()->is('cart')) --}}
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Your Cart</span>
                                    <div class="qty cart-qty">
                                        {{ auth()->check() ? auth()->user()->carts()->sum('jumlah') : 0 }}</div>
                                </a>
                                <div class="cart-dropdown">
                                    @include('front.partials.cart-items', [
                                        'cartItems' => auth()->check()
                                            ? auth()->user()->carts()->with('produk.gambarUtama')->latest()->take(3)->get()
                                            : [],
                                        'cartCount' => auth()->check() ? auth()->user()->carts()->count() : 0,
                                        'subtotal' => auth()->check()
                                            ? auth()->user()->carts()->with('produk')->get()->sum(function ($item) {
                                                    return $item->jumlah * $item->produk->harga_setelah_diskon;
                                                })
                                            : 0,
                                    ])
                                </div>
                            </div>
                            {{-- @endif --}}
                            <!-- /Cart -->

                            <!-- Menu Toogle -->
                            <div class="menu-toggle">
                                <a href="#">
                                    <i class="fa fa-bars"></i>
                                    <span>Menu</span>
                                </a>
                            </div>
                            <!-- /Menu Toogle -->
                        </div>
                    </div>
                    <!-- /ACCOUNT -->
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </div>
        <!-- /MAIN HEADER -->
    </header>
    <!-- /HEADER -->

    <!-- NAVIGATION -->
    <nav id="navigation">
        <!-- container -->
        <div class="container">
            <!-- responsive-nav -->
            <div id="responsive-nav">
                <!-- NAV -->
                <ul class="main-nav nav navbar-nav">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a
                            href="{{ route('home') }}">Home</a></li>
                    <li class="{{ request()->routeIs('produk.*') ? 'active' : '' }}"><a
                            href="{{ route('produk.index') }}">Produk</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            Simulasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('simulasi.index') }}" class="">Rakit</a></li>
                            <li>
                                <a href="{{ route('pelanggan.simulasi.list') }}" class="">List Simulasi</a>
                            </li>
                            <li>
                                <a href="{{ route('simulasi.rekomendasi') }}" class="">Rekomendasi Build</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- /NAV -->
            </div>
            <!-- /responsive-nav -->
        </div>
        <!-- /container -->
    </nav>
    <!-- /NAVIGATION -->

    <!-- SECTION -->
    @yield('content')
    <!-- /SECTION -->

    <!-- NEWSLETTER -->
    {{-- <div id="newsletter" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="newsletter">
                        <p>Sign Up for the <strong>NEWSLETTER</strong></p>
                        <form>
                            <input class="input" type="email" placeholder="Enter Your Email">
                            <button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribe</button>
                        </form>
                        <ul class="newsletter-follow">
                            <li>
                                <a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div> --}}
    <!-- /NEWSLETTER -->

    <!-- FOOTER -->
    <footer id="footer">
        <!-- top footer -->
        <div class="section">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row justify-content-center">
                    <div class="col-md-3"></div>
                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">About Us</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut.</p>
                            <ul class="footer-links">
                                <li><a href="#"><i class="fa fa-map-marker"></i>1734 Stonecoal Road</a></li>
                                <li><a href="#"><i class="fa fa-phone"></i>+021-95-51-84</a></li>
                                <li><a href="#"><i class="fa fa-envelope-o"></i>email@email.com</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Categories</h3>
                            <ul class="footer-links">
                                <li><a href="#">Hot deals</a></li>
                                <li><a href="#">Laptops</a></li>
                                <li><a href="#">Smartphones</a></li>
                                <li><a href="#">Cameras</a></li>
                                <li><a href="#">Accessories</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="clearfix visible-xs"></div>
                    <div class="col-md-3"></div>


                    {{-- <div class="col-md-3 col-xs-6">
                            <div class="footer">
                                <h3 class="footer-title">Information</h3>
                                <ul class="footer-links">
                                    <li><a href="#">About Us</a></li>
                                    <li><a href="#">Contact Us</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Orders and Returns</a></li>
                                    <li><a href="#">Terms & Conditions</a></li>
                                </ul>
                            </div>
                        </div> --}}

                    {{-- <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Service</h3>
                            <ul class="footer-links">
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">View Cart</a></li>
                                <li><a href="#">Wishlist</a></li>
                                <li><a href="#">Track My Order</a></li>
                                <li><a href="#">Help</a></li>
                            </ul>
                        </div>
                    </div> --}}
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /top footer -->

        <!-- bottom footer -->
        <div id="bottom-footer" class="section">
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul class="footer-payments">
                            <li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
                            <li><a href="#"><i class="fa fa-credit-card"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
                        </ul>
                        <span class="copyright">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This template is made with <i
                                class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com"
                                target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </span>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bottom footer -->
    </footer>
    <!-- /FOOTER -->

    <!-- Modal Tambah Alamat -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog"
        aria-labelledby="addAddressModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addressForm" action="{{ route('pelanggan.alamat.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="addAddressModalLabel">Tambah Alamat Baru</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <input type="text" class="input" name="nama_penerima" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" class="input" name="nomor_telepon" required>
                        </div>
                        <div class="form-group" style="display: none;">
                            <input type="hidden" name="api_id" value="">
                            <input type="hidden" name="provinsi" class="input">
                            <input type="hidden" name="kota" id="kota" class="input">
                            <input type="hidden" class="input" name="kecamatan">
                            <input type="hidden" class="input" name="kelurahan">
                            <input type="hidden" class="input" name="kode_pos">
                            <input type="hidden" class="input" name="label">
                        </div>
                        <div class="form-group">
                            <label for="cari-alamat">Cari Alamat</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="cari-alamat"
                                    placeholder="Cari Alamat">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group" style="display: hidden;">
                            <label>Detail ALamat</label>
                            <textarea class="input" name="alamat_lengkap" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="number" class="form-control" name="kode_pos">
                        </div>
                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea class="input" name="catatan" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="input-checkbox">
                                <input type="checkbox" name="is_utama" id="isUtama" value="1">
                                <label for="isUtama">
                                    <span></span>
                                    Jadikan alamat utama
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pencarian Alamat -->
    <div class="modal fade" id="searchAddressModal" tabindex="-1" role="dialog"
        aria-labelledby="searchAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchAddressModalLabel">Cari Alamat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="addressSearchInput"
                                placeholder="Masukkan kata kunci alamat...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" id="searchAddressBtn">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="search-results">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Alamat Lengkap</th>
                                    <th>Kode Pos</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="addressResults">
                                <!-- Hasil pencarian akan muncul di sini -->
                            </tbody>
                        </table>
                        <div id="noResults" style="display: none;">
                            <p class="text-center text-muted">Tidak ada hasil ditemukan</p>
                        </div>
                        <div id="searchLoading" style="display: none;">
                            <p class="text-center"><i class="fa fa-spinner fa-spin"></i> Mencari alamat...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="searchAddressModal" tabindex="-1" role="dialog"
        aria-labelledby="searchAddressModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="searchAddressModalLabel">Cari Alamat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="addressSearchInput"
                                placeholder="Masukkan kata kunci alamat...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" id="searchAddressBtn">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="search-results">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Alamat Lengkap</th>
                                    <th>Kode Pos</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="addressResults">
                                <!-- Hasil pencarian akan muncul di sini -->
                            </tbody>
                        </table>
                        <div id="noResults" style="display: none;">
                            <p class="text-center text-muted">Tidak ada hasil ditemukan</p>
                        </div>
                        <div id="searchLoading" style="display: none;">
                            <p class="text-center"><i class="fa fa-spinner fa-spin"></i> Mencari alamat...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div> --}}

    <div id="ajax-loader" style="display:none;">
        <div class="spinner-overlay">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- jQuery Plugins -->
    <script src="{{ asset('front') }}/js/jquery.min.js"></script>
    <script src="{{ asset('front') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('front') }}/js/slick.min.js"></script>
    <script src="{{ asset('front') }}/js/nouislider.min.js"></script>
    <script src="{{ asset('front') }}/js/jquery.zoom.min.js"></script>
    <script src="{{ asset('front') }}/js/main.js"></script>
    <script src="{{ asset('front/js/custom.js') }}"></script>

    <script>
        // Buka modal pencarian saat field cari alamat diklik
        // $('input[name="cari-alamat"]').click(function() {
        //     $('#searchAddressModal').modal('show');
        // });
    </script>

    @stack('front-script')

</body>

</html>
