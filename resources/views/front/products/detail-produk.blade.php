@extends('front.layouts.main')

@section('title', $product->nama . ' - ' . config('app.name'))

@section('content')
    <!-- BREADCRUMB -->
    {{-- <div id="breadcrumb" class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb-tree">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a
                                href="{{ route('produk.kategori', $product->kategori->slug) }}">{{ $product->kategori->nama }}</a>
                        </li>
                        @if ($product->brand)
                            <li><a href="{{ route('produk.brand', $product->brand->slug) }}">{{ $product->brand->nama }}</a>
                            </li>
                        @endif
                        <li class="active">{{ $product->nama }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <div class="container">
            <div class="row">
                <!-- Product main img -->
                <div class="col-md-5 col-md-push-2">
                    <div id="product-main-img">
                        @foreach ($product->gambar as $gambar)
                            <div class="product-preview">
                                <img src="{{ asset('storage/' . $gambar->gambar) }}" alt="{{ $product->nama }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /Product main img -->

                <!-- Product thumb imgs -->
                <div class="col-md-2 col-md-pull-5">
                    <div id="product-imgs">
                        @foreach ($product->gambar as $gambar)
                            <div class="product-preview">
                                <img src="{{ asset('storage/' . $gambar->gambar) }}" alt="{{ $product->nama }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /Product thumb imgs -->

                <!-- Product details -->
                <div class="col-md-5">
                    <div class="product-details">
                        <h2 class="product-name">{{ $product->nama }}</h2>
                        <div>
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
                            <a class="review-link" href="#reviews">{{ $product->ulasan->count() }} Review(s) | Add your
                                review</a>
                        </div>
                        <div>
                            @if ($product->diskon > 0)
                                <h3 class="product-price">Rp
                                    {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}
                                    <del class="product-old-price">Rp
                                        {{ number_format($product->harga, 0, ',', '.') }}</del>
                                    <span class="discount">-{{ $product->diskon }}%</span>
                                </h3>
                            @else
                                <h3 class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
                            @endif
                            <span
                                class="product-available @if ($product->stok > 0) in-stock @else out-of-stock @endif">
                                @if ($product->stok > 0)
                                    In Stock ({{ $product->stok }})
                                @else
                                    Out of Stock
                                @endif
                            </span>
                        </div>

                        <p>{{ $product->deskripsi_singkat }}</p>

                        @if ($product->spesifikasi->count() > 0)
                            <div class="product-options">
                                <h4>Spesifikasi Utama:</h4>
                                <ul>
                                    @foreach ($product->spesifikasi->take(3) as $spesifikasi)
                                        <li><strong>{{ $spesifikasi->nama }}:</strong> {{ $spesifikasi->nilai }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($product->stok > 0)
                            <div class="add-to-cart">
                                <div class="qty-label">
                                    Qty
                                    <div class="input-number">
                                        <input type="number" id="qty" value="1" min="1"
                                            max="{{ $product->stok }}">
                                        <span class="qty-up">+</span>
                                        <span class="qty-down">-</span>
                                    </div>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }}, $('#qty').val())">
                                    <i class="fa fa-shopping-cart"></i> add to cart
                                </button>
                            </div>
                        @endif

                        <ul class="product-btns">
                            <li>
                                <a href="#" onclick="toggleWishlist({{ $product->id }})">
                                    <i class="fa fa-heart-o"></i> add to wishlist
                                </a>
                            </li>
                            @if ($product->motherboard)
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#compatibilityModal">
                                        <i class="fa fa-check-circle"></i> check compatibility
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <ul class="product-links">
                            <li>Category:</li>
                            <li><a
                                    href="{{ route('produk.kategori', $product->kategori->slug) }}">{{ $product->kategori->nama }}</a>
                            </li>
                            @if ($product->brand)
                                <li>Brand:</li>
                                <li><a
                                        href="{{ route('produk.brand', $product->brand->slug) }}">{{ $product->brand->nama }}</a>
                                </li>
                            @endif
                        </ul>

                        <ul class="product-links">
                            <li>Share:</li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /Product details -->

                <!-- Product tab -->
                <div class="col-md-12">
                    <div id="product-tab">
                        <!-- product tab nav -->
                        <ul class="tab-nav">
                            <li class="active"><a data-toggle="tab" href="#tab1">Description</a></li>
                            <li><a data-toggle="tab" href="#tab2">Details</a></li>
                            <li><a data-toggle="tab" href="#tab3" id="reviews-tab">Reviews
                                    ({{ $product->ulasan->count() }})</a></li>
                        </ul>
                        <!-- /product tab nav -->

                        <!-- product tab content -->
                        <div class="tab-content">
                            <!-- tab1  -->
                            <div id="tab1" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>{!! nl2br(e($product->deskripsi)) !!}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /tab1  -->

                            <!-- tab2  -->
                            <div id="tab2" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Spesifikasi Lengkap</h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                @foreach ($product->spesifikasi as $spesifikasi)
                                                    <tr>
                                                        <th width="30%">{{ $spesifikasi->nama }}</th>
                                                        <td>{{ $spesifikasi->nilai }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        @if ($product->motherboard)
                                            <h4>Kompatibilitas</h4>
                                            <p>Produk ini kompatibel dengan:
                                                <a href="{{ route('produk.detail', $product->motherboard->slug) }}">
                                                    {{ $product->motherboard->nama }}
                                                </a>
                                            </p>
                                        @endif

                                        @if ($product->kompatibel_dengan_motherboard_ini->count() > 0)
                                            <h4>Produk yang Kompatibel</h4>
                                            <div class="row">
                                                @foreach ($product->kompatibel_dengan_motherboard_ini->take(4) as $produkKompatibel)
                                                    <div class="col-md-3 col-xs-6">
                                                        <div class="product">
                                                            <div class="product-img">
                                                                <img src="{{ $produkKompatibel->gambarUtama ? asset('storage/' . $produkKompatibel->gambarUtama->gambar) : asset('front/img/no-image.png') }}"
                                                                    alt="{{ $produkKompatibel->nama }}">
                                                            </div>
                                                            <div class="product-body">
                                                                <h3 class="product-name"><a
                                                                        href="{{ route('produk.detail', $produkKompatibel->slug) }}">{{ $produkKompatibel->nama }}</a>
                                                                </h3>
                                                                <h4 class="product-price">Rp
                                                                    {{ number_format($produkKompatibel->harga_setelah_diskon, 0, ',', '.') }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- /tab2  -->

                            <!-- tab3  -->
                            <div id="tab3" class="tab-pane fade in">
                                <div class="row">
                                    <!-- Rating -->
                                    <div class="col-md-3">
                                        <div id="rating">
                                            <div class="rating-avg">
                                                <span>{{ number_format($product->rating, 1) }}</span>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($product->rating))
                                                            <i class="fa fa-star"></i>
                                                        @elseif($i - 0.5 <= $product->rating)
                                                            <i class="fa fa-star-half-o"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <ul class="rating">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <li>
                                                        <div class="rating-stars">
                                                            @for ($j = 1; $j <= 5; $j++)
                                                                @if ($j <= $i)
                                                                    <i class="fa fa-star"></i>
                                                                @else
                                                                    <i class="fa fa-star-o"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <div class="rating-progress">
                                                            @php
                                                                $count = $product->ulasan->where('rating', $i)->count();
                                                                $percentage =
                                                                    $product->ulasan->count() > 0
                                                                        ? ($count / $product->ulasan->count()) * 100
                                                                        : 0;
                                                            @endphp
                                                            <div style="width: {{ $percentage }}%;"></div>
                                                        </div>
                                                        <span class="sum">{{ $count }}</span>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /Rating -->

                                    <!-- Reviews -->
                                    <div class="col-md-6">
                                        <div id="reviews">
                                            <ul class="reviews">
                                                @foreach ($product->ulasan as $ulasan)
                                                    <li>
                                                        <div class="review-heading">
                                                            <h5 class="name">{{ $ulasan->pengguna->name }}</h5>
                                                            <p class="date">
                                                                {{ $ulasan->created_at->format('d M Y, H:i') }}</p>
                                                            <div class="review-rating">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $ulasan->rating)
                                                                        <i class="fa fa-star"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o empty"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="review-body">
                                                            <p>{{ $ulasan->komentar }}</p>
                                                            @if ($ulasan->gambar)
                                                                <div class="review-images">
                                                                    <img src="{{ asset('storage/ulasan/' . $ulasan->gambar) }}"
                                                                        alt="Review Image"
                                                                        style="max-width: 150px; max-height: 150px;">
                                                                </div>
                                                            @endif
                                                            @if ($ulasan->balasan_admin)
                                                                <div class="admin-reply">
                                                                    <strong>Admin:</strong> {{ $ulasan->balasan_admin }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="reviews-pagination">
                                                {{ $ulasan->appends(request()->query())->links('front.pagination') }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Reviews -->

                                    <!-- Review Form -->
                                    @auth
                                        <div class="col-md-3">
                                            <div id="review-form">
                                                <form class="review-form"
                                                    action="{{ route('produk.ulasan.store', $product->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="pesanan_id" value="{{ $pesananId ?? '' }}">
                                                    <input class="input" type="text" placeholder="Your Name"
                                                        value="{{ auth()->user()->name }}" readonly>
                                                    <input class="input" type="email" placeholder="Your Email"
                                                        value="{{ auth()->user()->email }}" readonly>
                                                    <textarea class="input" placeholder="Your Review" name="komentar" required></textarea>
                                                    <div class="input-rating">
                                                        <span>Your Rating: </span>
                                                        <div class="stars">
                                                            <input id="star5" name="rating" value="5"
                                                                type="radio" required><label for="star5"></label>
                                                            <input id="star4" name="rating" value="4"
                                                                type="radio"><label for="star4"></label>
                                                            <input id="star3" name="rating" value="3"
                                                                type="radio"><label for="star3"></label>
                                                            <input id="star2" name="rating" value="2"
                                                                type="radio"><label for="star2"></label>
                                                            <input id="star1" name="rating" value="1"
                                                                type="radio"><label for="star1"></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="gambar">Upload Image (Optional)</label>
                                                        <input type="file" class="form-control" name="gambar"
                                                            accept="image/*">
                                                    </div>
                                                    <button type="submit" class="primary-btn">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-3">
                                            <div class="alert alert-info">
                                                <p>Please <a href="{{ route('login') }}">login</a> to submit a review.</p>
                                                <p>Only customers who have purchased this product can submit a review.</p>
                                            </div>
                                        </div>
                                    @endauth
                                    <!-- /Review Form -->
                                </div>
                            </div>
                            <!-- /tab3  -->
                        </div>
                        <!-- /product tab content  -->
                    </div>
                </div>
                <!-- /product tab -->
            </div>
        </div>
    </div>
    <!-- /SECTION -->

    <!-- Section -->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h3 class="title">Related Products</h3>
                    </div>
                </div>

                @foreach ($relatedProducts as $relatedProduct)
                    <div class="col-md-3 col-xs-6">
                        <div class="product">
                            <div class="product-img">
                                <img src="{{ $relatedProduct->gambarUtama ? asset('storage/' . $relatedProduct->gambarUtama->gambar) : asset('front/img/no-image.png') }}"
                                    alt="{{ $relatedProduct->nama }}">
                                @if ($relatedProduct->diskon > 0)
                                    <div class="product-label">
                                        <span class="sale">-{{ $relatedProduct->diskon }}%</span>
                                    </div>
                                @endif
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $relatedProduct->kategori->nama }}</p>
                                <h3 class="product-name"><a
                                        href="{{ route('produk.detail', $relatedProduct->slug) }}">{{ $relatedProduct->nama }}</a>
                                </h3>
                                <h4 class="product-price">
                                    Rp {{ number_format($relatedProduct->harga_setelah_diskon, 0, ',', '.') }}
                                    @if ($relatedProduct->diskon > 0)
                                        <del class="product-old-price">Rp
                                            {{ number_format($relatedProduct->harga, 0, ',', '.') }}</del>
                                    @endif
                                </h4>
                                <div class="product-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $relatedProduct->rating)
                                            <i class="fa fa-star"></i>
                                        @elseif($i - 0.5 <= $relatedProduct->rating)
                                            <i class="fa fa-star-half-o"></i>
                                        @else
                                            <i class="fa fa-star-o"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="product-btns">
                                    <button class="add-to-wishlist"
                                        onclick="toggleWishlist({{ $relatedProduct->id }})"><i
                                            class="fa fa-heart-o"></i><span class="tooltipp">add to
                                            wishlist</span></button>
                                    <button class="quick-view"
                                        onclick="location.href='{{ route('produk.detail', $relatedProduct->slug) }}'"><i
                                            class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                </div>
                            </div>
                            <div class="add-to-cart">
                                <button class="add-to-cart-btn" onclick="addToCart({{ $relatedProduct->id }})"><i
                                        class="fa fa-shopping-cart"></i> add to cart</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- /Section -->

    <!-- Compatibility Modal -->
    @if ($product->motherboard)
        <div class="modal fade" id="compatibilityModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Compatibility Check</h4>
                    </div>
                    <div class="modal-body">
                        <p>This product is compatible with:</p>
                        <div class="product">
                            <div class="product-img">
                                <img src="{{ $product->motherboard->gambarUtama ? asset('storage/' . $product->motherboard->gambarUtama->gambar) : asset('front/img/no-image.png') }}"
                                    alt="{{ $product->motherboard->nama }}">
                            </div>
                            <div class="product-body">
                                <h3 class="product-name"><a
                                        href="{{ route('produk.detail', $product->motherboard->slug) }}">{{ $product->motherboard->nama }}</a>
                                </h3>
                                <h4 class="product-price">Rp
                                    {{ number_format($product->motherboard->harga_setelah_diskon, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="{{ route('produk.detail', $product->motherboard->slug) }}" class="btn btn-primary">View
                            Product</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('front-script')
    <script>
        $(document).ready(function() {
            // Handle pagination for reviews
            $(document).on('click', '.reviews-pagination a', function(e) {
                e.preventDefault();

                // Get the page URL
                const url = $(this).attr('href');

                // Show loading indicator if needed
                $('#ajax-loader').show();

                // Load the reviews via AJAX
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Replace the reviews section
                        $('#reviews').html($(response).find('#reviews').html());

                        // Scroll to reviews section
                        $('html, body').animate({
                            scrollTop: $('#reviews-tab').offset().top - 100
                        }, 500);
                    },
                    complete: function() {
                        $('#ajax-loader').hide();
                    }
                });
            });
        });
    </script>
@endpush
