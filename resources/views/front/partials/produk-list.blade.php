@foreach ($products as $product)
    <div class="col-md-4 col-xs-6">
        <div class="product" data-product-id="{{ $product->id }}">
            <div class="product-img">
                <img src="{{ $product->gambarUtama ? asset('storage/' . $product->gambarUtama->gambar) : asset('front/default-image.jpg') }}"
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
                <h3 class="product-name"><a href="{{ route('produk.detail', $product->slug) }}">{{ $product->nama }}</a>
                </h3>
                <h4 class="product-price">
                    Rp {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}
                    @if ($product->diskon > 0)
                        <del class="product-old-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</del>
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
                    <button class="add-to-wishlist" onclick="addToWishlist({{ $product->id }})"><i
                            class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                    <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick
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
