@extends('front.layouts.main')

@section('title', 'Home')

@section('content')
    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- shop -->
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('front') }}/img/shop01.png" alt="">
                        </div>
                        <div class="shop-body">
                            <h3>Laptop<br>Collection</h3>
                            <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /shop -->

                <!-- shop -->
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('front') }}/img/shop03.png" alt="">
                        </div>
                        <div class="shop-body">
                            <h3>Accessories<br>Collection</h3>
                            <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /shop -->

                <!-- shop -->
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('front') }}/img/shop02.png" alt="">
                        </div>
                        <div class="shop-body">
                            <h3>Cameras<br>Collection</h3>
                            <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /shop -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">New Products</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                <li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
                                <li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
                                <li><a data-toggle="tab" href="#tab1">Cameras</a></li>
                                <li><a data-toggle="tab" href="#tab1">Accessories</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab1" class="tab-pane active">
                                <div class="products-slick" data-nav="#slick-nav-1">
                                    @foreach ($products as $product)
                                        <!-- product -->
                                        <div class="product">
                                            <div class="product-img">
                                                @if ($product->gambarUtama)
                                                    <img src="{{ asset('storage/' . $product->gambarUtama->gambar) }}"
                                                        alt="{{ $product->nama }}">
                                                @else
                                                    <img src="{{ asset('storage/default-image.png') }}" alt="Default Image">
                                                @endif
                                                @if ($product->diskon)
                                                    <div class="product-label">
                                                        <span class="sale">-{{ $product->diskon }}%</span>
                                                        @if ($product->is_new)
                                                            <span class="new">NEW</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category">{{ $product->kategori->nama }}</p>
                                                <h3 class="product-name"><a href="">{{ $product->nama }}</a>
                                                </h3>
                                                <h4 class="product-price">
                                                    Rp {{ number_format($product->harga_diskon, 0, ',', '.') }}
                                                    @if ($product->diskon)
                                                        <del class="product-old-price">Rp
                                                            {{ number_format($product->harga, 0, ',', '.') }}</del>
                                                    @endif
                                                </h4>
                                                <div class="product-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $product->rating)
                                                            <i class="fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="product-btns">
                                                    <button class="add-to-wishlist"
                                                        onclick="toggleWishlist({{ $product->id }})">
                                                        <i
                                                            class="fa fa-heart{{ auth()->check() && auth()->user()->wishlists()->where('produk_id', $product->id)->exists() ? '' : '-o' }}"></i>
                                                        <span class="tooltipp">add to wishlist</span>
                                                    </button>
                                                    <button class="add-to-compare"><i class="fa fa-exchange"></i><span
                                                            class="tooltipp">add to compare</span></button>
                                                    <button class="quick-view"><i class="fa fa-eye"></i><span
                                                            class="tooltipp">quick view</span></button>
                                                </div>
                                            </div>
                                            <div class="add-to-cart">
                                                <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> add to cart
                                                </button>
                                            </div>
                                        </div>
                                        <!-- /product -->
                                    @endforeach
                                </div>
                                <div id="slick-nav-1" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- HOT DEAL SECTION -->
    <div id="hot-deal" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="hot-deal">
                        <ul class="hot-deal-countdown">
                            <li>
                                <div>
                                    <h3>02</h3>
                                    <span>Days</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3>10</h3>
                                    <span>Hours</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3>34</h3>
                                    <span>Mins</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3>60</h3>
                                    <span>Secs</span>
                                </div>
                            </li>
                        </ul>
                        <h2 class="text-uppercase">hot deal this week</h2>
                        <p>New Collection Up to 50% OFF</p>
                        <a class="primary-btn cta-btn" href="#">Shop now</a>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /HOT DEAL SECTION -->

    <!-- SECTION - Top Selling Products -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Top selling</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                <li class="active"><a data-toggle="tab" href="#tab2">Laptops</a></li>
                                <li><a data-toggle="tab" href="#tab2">Smartphones</a></li>
                                <li><a data-toggle="tab" href="#tab2">Cameras</a></li>
                                <li><a data-toggle="tab" href="#tab2">Accessories</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab2" class="tab-pane fade in active">
                                <div class="products-slick" data-nav="#slick-nav-2">
                                    @foreach ($topSelling as $product)
                                        <!-- product -->
                                        <div class="product">
                                            <div class="product-img">
                                                @if ($product->gambarUtama)
                                                    <img src="{{ asset('storage/' . $product->gambarUtama->gambar) }}"
                                                        alt="{{ $product->nama }}">
                                                @else
                                                    <img src="{{ asset('storage/default-image.png') }}"
                                                        alt="Default Image">
                                                @endif
                                                @if ($product->diskon)
                                                    <div class="product-label">
                                                        <span class="sale">-{{ $product->diskon }}%</span>
                                                        @if ($product->is_new)
                                                            <span class="new">NEW</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category">{{ $product->kategori->nama }}</p>
                                                <h3 class="product-name"><a href="">{{ $product->nama }}</a>
                                                </h3>
                                                <h4 class="product-price">
                                                    Rp {{ number_format($product->harga_diskon, 0, ',', '.') }}
                                                    @if ($product->diskon)
                                                        <del class="product-old-price">Rp
                                                            {{ number_format($product->harga, 0, ',', '.') }}</del>
                                                    @endif
                                                </h4>
                                                <div class="product-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $product->rating)
                                                            <i class="fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="product-btns">
                                                    @livewire('wishlist-button', ['product' => $product], key('wishlist-ts-' . $product->id))
                                                    <button class="add-to-compare"><i class="fa fa-exchange"></i><span
                                                            class="tooltipp">add to compare</span></button>
                                                    <button class="quick-view"><i class="fa fa-eye"></i><span
                                                            class="tooltipp">quick view</span></button>
                                                </div>
                                            </div>
                                            <livewire:add-to-cart-button :product="$product" :key="'cart-ts-' . $product->id" />
                                        </div>
                                        <!-- /product -->
                                    @endforeach
                                </div>
                                <div id="slick-nav-2" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->
@endsection
