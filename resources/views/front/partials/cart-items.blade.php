<div class="cart-list">
    @foreach($cartItems as $item)
    <div class="product-widget" data-cart-item-id="{{ $item->id }}">
        <div class="product-img">
            <img src="{{ asset('storage/' . optional($item->produk->gambarUtama)->gambar) }}" alt="">
        </div>
        <div class="product-body">
            <h3 class="product-name"><a href="#">{{ $item->produk->nama }}</a></h3>
            <h4 class="product-price">
                <span class="qty">{{ $item->jumlah }}x</span>
                Rp {{ number_format($item->produk->harga_setelah_diskon, 0, ',', '.') }}
            </h4>
        </div>
        <button class="delete" onclick="removeCartItem({{ $item->id }}, event)">
            <i class="fa fa-close"></i>
        </button>
    </div>
    @endforeach
</div>
<div class="cart-summary">
    <small>{{ $cartCount }} Item(s) selected</small>
    <h5>SUBTOTAL: Rp {{ number_format($subtotal, 0, ',', '.') }}</h5>
</div>
<div class="cart-btns">
    <a href="{{ route('pelanggan.cart.index') }}">View Cart</a>
    <a href="">Checkout <i class="fa fa-arrow-circle-right"></i></a>
</div>