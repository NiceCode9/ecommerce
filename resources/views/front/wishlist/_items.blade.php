@forelse($wishlists as $item)
    <div class="product-widget" data-wishlist-id="{{ $item->id }}">
        <div class="product-img">
            <img src="{{ asset('storage/' . optional($item->produk->gambarUtama)->gambar) }}" alt="{{ $item->produk->nama }}">
        </div>
        <div class="product-body">
            <h3 class="product-name">
                {{-- <a href="{{ route('products.show', $item->produk->slug) }}">{{ $item->produk->nama }}</a> --}}
                <a href="">{{ $item->produk->nama }}</a>
            </h3>
            <h4 class="product-price">Rp {{ number_format($item->produk->harga_setelah_diskon, 0, ',', '.') }}</h4>
            <div class="product-btns">
                <button class="add-to-cart-btn btn btn-info btn-sm" onclick="addToCart({{ $item->produk->id }})">
                    <i class="fa fa-shopping-cart"></i> Add to Cart
                </button>
                <button class="wishlist-remove-btn btn-danger btn btn-sm" onclick="removeFromWishlist({{ $item->id }})">
                    <i class="fa fa-trash"></i> Remove
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-wishlist">
        <i class="fa fa-heart-o"></i>
        <p>Your wishlist is empty</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Browse Products</a>
    </div>
@endforelse