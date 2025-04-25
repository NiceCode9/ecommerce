@extends('front.layouts.main')

@push('style')
    <style>
        .price-filter {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .price-filter .input-number {
            display: flex;
            flex-direction: column;
        }

        .price-filter label {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .price-filter input {
            width: 100%;
            padding: 8px;
            border: 1px solid #e4e4e4;
            border-radius: 4px;
        }
    </style>
@endpush

@section('title', 'Shop - ' . config('app.name'))

@section('content')
    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb-tree">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li class="active">Shop</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <div class="container">
            <div class="row">
                <!-- ASIDE -->
                <div id="aside" class="col-md-3">
                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Categories</h3>
                        <div class="checkbox-filter">
                            @foreach ($categories as $category)
                                <div class="input-checkbox">
                                    <input type="checkbox" id="category-{{ $category->id }}" name="categories[]"
                                        value="{{ $category->id }}" @if (in_array($category->id, request('categories', []))) checked @endif
                                        onchange="filterProducts()">
                                    <label for="category-{{ $category->id }}">
                                        <span></span>
                                        {{ $category->nama }}
                                        <small>({{ $category->produk_count }})</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /aside Widget -->

                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Price</h3>
                        <div class="price-filter">
                            <div class="input-number price-min">
                                <label for="price-min">Min Price:</label>
                                <input id="price-min" type="number" name="min_price" value="{{ request('min_price', 0) }}"
                                    min="0" max="{{ $maxPrice }}" onchange="filterProducts()">
                            </div>
                            <div class="input-number price-max">
                                <label for="price-max">Max Price:</label>
                                <input id="price-max" type="number" name="max_price"
                                    value="{{ request('max_price', $maxPrice) }}" min="0" max="{{ $maxPrice }}"
                                    onchange="filterProducts()">
                            </div>
                        </div>
                    </div>
                    <!-- /aside Widget -->

                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Brand</h3>
                        <div class="checkbox-filter">
                            @foreach ($brands as $brand)
                                <div class="input-checkbox">
                                    <input type="checkbox" id="brand-{{ $brand->id }}" name="brands[]"
                                        value="{{ $brand->id }}" @if (in_array($brand->id, request('brands', []))) checked @endif
                                        onchange="filterProducts()">
                                    <label for="brand-{{ $brand->id }}">
                                        <span></span>
                                        {{ $brand->nama }}
                                        <small>({{ $brand->products_count }})</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /aside Widget -->

                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Top selling</h3>
                        @foreach ($topSelling as $product)
                            <div class="product-widget">
                                <div class="product-img">
                                    <img src="{{ $product->gambarUtama ? asset('storage/produk/' . $product->gambarUtama->gambar) : asset('front/img/no-image.png') }}"
                                        alt="{{ $product->nama }}">
                                </div>
                                <div class="product-body">
                                    <p class="product-category">{{ $product->kategori->nama }}</p>
                                    <h3 class="product-name"><a
                                            href="{{ route('produk.detail', $product->slug) }}">{{ $product->nama }}</a>
                                    </h3>
                                    <h4 class="product-price">Rp
                                        {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- /aside Widget -->
                </div>
                <!-- /ASIDE -->

                <!-- STORE -->
                <div id="store" class="col-md-9">
                    <!-- store top filter -->
                    <div class="store-filter clearfix">
                        <div class="store-sort">
                            <label>
                                Sort By:
                                <select class="input-select" name="sort" onchange="filterProducts()">
                                    <option value="popular" @if (request('sort') == 'popular') ) selected @endif>Popular
                                    </option>
                                    <option value="newest" @if (request('sort') == 'newest') ) selected @endif>Newest
                                    </option>
                                    <option value="price_low" @if (request('sort') == 'price_low') ) selected @endif>Price: Low
                                        to High</option>
                                    <option value="price_high" @if (request('sort') == 'price_high') ) selected @endif>Price:
                                        High to Low</option>
                                    <option value="rating" @if (request('sort') == 'rating') ) selected @endif>Rating
                                    </option>
                                </select>
                            </label>

                            <label>
                                Show:
                                <select class="input-select" name="per_page" onchange="filterProducts()">
                                    <option value="12" @if (request('per_page') == 12) ) selected @endif>12</option>
                                    <option value="24" @if (request('per_page') == 24) ) selected @endif>24</option>
                                    <option value="48" @if (request('per_page') == 48) ) selected @endif>48</option>
                                </select>
                            </label>
                        </div>
                        <ul class="store-grid">
                            <li class="active"><i class="fa fa-th"></i></li>
                            <li><a href="#"><i class="fa fa-th-list"></i></a></li>
                        </ul>
                    </div>
                    <!-- /store top filter -->

                    <!-- store products -->
                    <div class="row" id="product-list">
                        @foreach ($products as $product)
                            <div class="col-md-4 col-xs-6">
                                <div class="product">
                                    <div class="product-img">
                                        <img src="{{ $product->gambarUtama ? asset('storage/produk/' . $product->gambarUtama->gambar) : asset('front/img/no-image.png') }}"
                                            alt="{{ $product->nama }}">
                                        @if ($product->diskon > 0)
                                            <div class="product-label">
                                                <span class="sale">-{{ $product->diskon }}%</span>
                                            </div>
                                        @endif
                                        @if ($product->created_at > now()->subDays(7))
                                            <div class="product-label">
                                                <span class="new">NEW</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{ $product->kategori->nama }}</p>
                                        <h3 class="product-name"><a
                                                href="{{ route('produk.detail', $product->slug) }}">{{ $product->nama }}</a>
                                        </h3>
                                        <h4 class="product-price">
                                            Rp {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}
                                            @if ($product->diskon > 0)
                                                <del class="product-old-price">Rp
                                                    {{ number_format($product->harga, 0, ',', '.') }}</del>
                                            @endif
                                        </h4>
                                        <div class="product-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $product->rating)
                                                    <i class="fa fa-star"></i>
                                                @elseif($i - 0.5 <= $product->rating)
                                                    <i class="fa fa-star-half-o"></i>
                                                @else
                                                    <i class="fa fa-star-o"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="product-btns">
                                            <button class="add-to-wishlist"
                                                onclick="toggleWishlist({{ $product->id }})"><i
                                                    class="fa fa-heart-o"></i><span class="tooltipp">add to
                                                    wishlist</span></button>
                                            <button class="quick-view"
                                                onclick="location.href='{{ route('produk.detail', $product->slug) }}'"><i
                                                    class="fa fa-eye"></i><span class="tooltipp">quick
                                                    view</span></button>
                                        </div>
                                    </div>
                                    <div class="add-to-cart">
                                        <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})"><i
                                                class="fa fa-shopping-cart"></i> add to cart</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- /store products -->

                    <!-- store bottom filter -->
                    {{-- <div class="store-filter clearfix">
                        <span class="store-qty">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                            {{ $products->total() }} products</span>
                        {{ $products->appends(request()->query())->links('front.pagination') }}
                    </div> --}}
                    @if ($products->hasPages())
                        <div class="store-filter clearfix">
                            <span class="store-qty">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                                {{ $products->total() }} products</span>
                            <div id="pagination-container">
                                {{ $products->appends(request()->query())->links('front.pagination') }}
                            </div>
                        </div>
                    @endif
                    <!-- /store bottom filter -->
                </div>
                <!-- /STORE -->
            </div>
        </div>
    </div>
    <!-- /SECTION -->
@endsection

@push('front-script')
    <script>
        function filterProducts() {
            const formData = {
                categories: [],
                brands: [],
                min_price: document.getElementById('price-min').value,
                max_price: document.getElementById('price-max').value,
                sort: document.querySelector('select[name="sort"]').value,
                per_page: document.querySelector('select[name="per_page"]').value
            };

            document.querySelectorAll('input[name="categories[]"]:checked').forEach(function(checkbox) {
                formData.categories.push(checkbox.value);
            });

            document.querySelectorAll('input[name="brands[]"]:checked').forEach(function(checkbox) {
                formData.brands.push(checkbox.value);
            });

            // Show loading
            $('#ajax-loader').show();

            $.ajax({
                url: '{{ route('produk.index') }}',
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#product-list').html(response.html);
                    $('#pagination-container').html(response.pagination);
                    $('.store-qty').text('Showing ' + response.showing + ' of ' + response.total + ' products');
                    history.pushState(null, null, '?' + $.param(formData));

                    // Re-attach event handlers for new pagination links
                    attachPaginationHandlers();
                },
                complete: function() {
                    $('#ajax-loader').hide();
                }
            });
        }

        function attachPaginationHandlers() {
            $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
                e.preventDefault();

                // Get the page URL
                const url = $(this).attr('href');

                // Show loading
                $('#ajax-loader').show();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#product-list').html(response.html);
                        $('#pagination-container').html(response.pagination);
                        $('.store-qty').text('Showing ' + response.showing + ' of ' + response.total +
                            ' products');
                    },
                    complete: function() {
                        $('#ajax-loader').hide();
                    }
                });
            });
        }

        // Initialize on page load
        $(document).ready(function() {
            attachPaginationHandlers();

            // Validasi input harga
            document.getElementById('price-min').addEventListener('change', function() {
                const maxPrice = document.getElementById('price-max');
                if (parseInt(this.value) > parseInt(maxPrice.value)) {
                    maxPrice.value = this.value;
                }
            });

            document.getElementById('price-max').addEventListener('change', function() {
                const minPrice = document.getElementById('price-min');
                if (parseInt(this.value) < parseInt(minPrice.value)) {
                    minPrice.value = this.value;
                }
            });
        });
    </script>
@endpush
