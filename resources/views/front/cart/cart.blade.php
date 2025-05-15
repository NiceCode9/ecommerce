@extends('front.layouts.main')

@push('style')
    <style>
        .dropdown-menu {
            overflow: visible !important;
        }

        /* Custom scrollbar */
        .cart-list::-webkit-scrollbar {
            width: 5px;
        }

        .cart-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .cart-list::-webkit-scrollbar-thumb {
            background: #D10024;
            border-radius: 5px;
        }

        .cart-list::-webkit-scrollbar-thumb:hover {
            background: #b3001b;
        }

        /* Product widget in cart */
        .product-widget {
            position: relative;
            padding-right: 30px;
        }

        .product-widget .delete {
            /* position: absolute;
                                                                                                                                                                                                                right: 0;
                                                                                                                                                                                                                top: 50%; */
            /* transform: translateY(-50%); */
            background: none;
            border: none;
            color: #ff0000;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .product-widget .delete:hover {
            opacity: 1;
        }

        /* Quantity Input */
        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-control button {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            cursor: pointer;
        }

        .quantity-control .qty-input {
            width: 50px;
            height: 30px;
            text-align: center;
            margin: 0 5px;
            border: 1px solid #ddd;
        }
    </style>
@endpush

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <form action="{{ route('pelanggan.checkout.index') }}" method="GET">
                    <div class="col-md-8">
                        <table class="cart-table">
                            @foreach (auth()->user()->carts()->with('produk')->get() as $item)
                                <tr data-cart-id="{{ $item->id }}">
                                    <td>
                                        <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}"
                                            class="cart-item-checkbox" checked>
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/' . optional($item->produk->gambarUtama)->gambar) }}"
                                            width="80">
                                    </td>
                                    <td>
                                        <h4>{{ $item->produk->nama }}</h4>
                                        <p>Rp {{ number_format($item->produk->harga_setelah_diskon, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <div class="quantity-control">
                                            <button type="button" class="qty-minus"
                                                onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                            <span class="qty">{{ $item->jumlah }}</span>
                                            <button type="button" class="qty-plus"
                                                onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                        </div>
                                    </td>
                                    <td class="subtotal">
                                        Rp
                                        {{ number_format($item->jumlah * $item->produk->harga_setelah_diskon, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <button class="remove-item" type="button"
                                            onclick="removeItem({{ $item->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="cart-summary">
                            <h4>Order Summary</h4>
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span class="cart-total">Rp
                                    {{ number_format(
                                        auth()->user()->carts()->with('produk')->get()->sum(function ($item) {
                                                return $item->jumlah * $item->produk->harga_setelah_diskon;
                                            }),
                                        0,
                                        ',',
                                        '.',
                                    ) }}</span>
                            </div>
                            {{-- <a href="javascipt:void(0)" class="btn btn-primary btn-block">Proceed to Checkout</a> --}}
                            <button type="submit" class="btn btn-primary btn-block">Checkout</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('front-script')
    <script>
        function removeItem(cartId) {
            removeCartItem(cartId, {
                removeRow: true
            });
        }
        $(document).ready(function() {
            $(document).on('change', '.cart-item-checkbox', function() {
                calculateSelectedTotal();
            });

            function calculateSelectedTotal() {
                let total = 0;
                $('.cart-item-checkbox:checked').each(function() {
                    const cartId = $(this).val();
                    const row = $(`[data-cart-id="${cartId}"]`);
                    const priceText = row.find('.subtotal').text().replace('Rp ', '').replace(/\./g, '');
                    total += parseInt(priceText);
                });
                $('.cart-total').text('Rp ' + total.toLocaleString('id-ID'));
            }

            // Panggil pertama kali
            calculateSelectedTotal();
        });
    </script>
@endpush
