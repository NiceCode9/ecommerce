{{-- <div class="quick-view-modal">
    <div class="row">
        <div class="col-md-6">
            <div class="product-gallery">
                <img src="{{ asset('storage/'. optional($product->gambarUtama)->gambar) }}" alt="{{ $product->nama }}">
                <div class="gallery-thumbs">
                    @foreach($product->gambar as $gambar)
                        <img src="{{ asset('storage/'. optional($gambar->gambar)) }}" alt="Thumbnail">
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2>{{ $product->nama }}</h2>
            <div class="product-price">
                Rp {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}
                @if($product->diskon)
                    <del>Rp {{ number_format($product->harga, 0, ',', '.') }}</del>
                @endif
            </div>
            <div class="product-actions">
                <button onclick="addToCart({{ $product->id }})" class="btn btn-primary">
                    <i class="fa fa-shopping-cart"></i> Add to Cart
                </button>
                <button onclick="toggleWishlist({{ $product->id }})" class="btn btn-outline-secondary">
                    <i class="fa fa-heart{{ auth()->check() && auth()->user()->wishlists()->where('produk_id', $product->id)->exists() ? '' : '-o' }}"></i>
                </button>
            </div>
            <div class="product-description">
                {!! $product->deskripsi !!}
            </div>
        </div>
    </div>
</div> --}}

<div class="quick-view-content">
    <div class="row">
        <div class="col-md-6">
            <div class="product-gallery">
                <img src="{{ $product->gambarUtama ? asset('storage/'.$product->gambarUtama->gambar) : asset('images/default-image.png') }}" 
                     alt="{{ $product->nama }}" 
                     class="main-image">
                <div class="gallery-thumbs">
                    @if($product->gambar->isNotEmpty())
                        @foreach($product->gambar as $gambar)
                        <img src="{{ asset('storage/'.$gambar->gambar) }}" 
                             alt="Thumbnail" 
                             onclick="changeMainImage(this.src)">
                        @endforeach
                    @else
                        <img src="{{ asset('images/default-thumbnail.png') }}" 
                             alt="Default Thumbnail">
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2>{{ $product->nama }}</h2>
            <div class="product-price">
                Rp {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}
                @if($product->diskon)
                    <del>Rp {{ number_format($product->harga, 0, ',', '.') }}</del>
                    <span class="discount">-{{ $product->diskon }}%</span>
                @endif
            </div>
            <div class="product-actions">
                <button onclick="addToCart({{ $product->id }})" class="btn btn-primary">
                    <i class="fa fa-shopping-cart"></i> Add to Cart
                </button>
                <button onclick="toggleWishlist({{ $product->id }})" class="btn btn-outline-secondary">
                    <i class="fa fa-heart{{ auth()->check() && auth()->user()->wishlists()->where('produk_id', $product->id)->exists() ? '' : '-o' }}"></i>
                </button>
            </div>
            <div class="product-description">
                {!! $product->deskripsi !!}
            </div>
        </div>
    </div>
</div>