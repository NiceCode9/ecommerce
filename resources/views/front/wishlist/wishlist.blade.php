@extends('front.layouts.main')

@push('style')
    <style>
        /* Wishlist Page */
        .wishlist-items {
            margin-top: 30px;
        }

        .product-widget {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 1px solid #eee;
            margin-bottom: 15px;
            position: relative;
        }

        .product-widget .product-img {
            width: 100px;
            margin-right: 20px;
        }

        .product-widget .product-img img {
            width: 100%;
            height: auto;
        }

        .product-body {
            flex-grow: 1;
        }

        .product-btns {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .wishlist-remove-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
        }

        .empty-wishlist {
            text-align: center;
            padding: 50px 0;
        }

        .empty-wishlist i {
            font-size: 50px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-wishlist p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Sorting Styles */
        .sort-options {
            margin-bottom: 20px;
            text-align: right;
        }

        .drd-sort {
            position: relative;
        }

        .drd-menu-sort {
            right: 0;
            left: auto;
        }

        .drd-menu-sort>li>a {
            padding: 8px 20px;
            color: #333;
        }

        .drd-menu-sort>li>a:hover {
            background: #f5f5f5;
            color: #D10024;
        }

        #currentSort {
            font-weight: bold;
            color: #D10024;
        }
    </style>
@endpush

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sort-options">
                        <div class="dropdown drd-sort">
                            <button class="btn btn-default dropdown-toggle" type="button" id="sortDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Sort by:
                                <span id="currentSort">
                                    @if (request('sort_by') == 'price_asc')
                                        Price (Low to High)
                                    @elseif(request('sort_by') == 'price_desc')
                                        Price (High to Low)
                                    @elseif(request('sort_by') == 'name_asc')
                                        Name (A-Z)
                                    @elseif(request('sort_by') == 'name_desc')
                                        Name (Z-A)
                                    @else
                                        Latest Added
                                    @endif
                                </span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu drd-menu-sort" aria-labelledby="sortDropdown">
                                <li><a href="#" data-sort="latest">Latest Added</a></li>
                                <li><a href="#" data-sort="price_asc">Price (Low to High)</a></li>
                                <li><a href="#" data-sort="price_desc">Price (High to Low)</a></li>
                                <li><a href="#" data-sort="name_asc">Name (A-Z)</a></li>
                                <li><a href="#" data-sort="name_desc">Name (Z-A)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-title">My Wishlist</h2>

                    <div class="wishlist-items">
                        @include('front.wishlist._items', ['wishlists' => $wishlists])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    {{ $wishlists->links('front.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('front-script')
    <script>
        let currentSort = 'latest';
        // Fungsi remove from wishlist
        function removeFromWishlist(wishlistId) {
            if (!confirm('Remove this item from wishlist?')) return;

            ajaxRequest({
                url: '/wishlist/' + wishlistId,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remove item dari DOM
                    $(`[data-wishlist-id="${wishlistId}"]`).fadeOut(300, function() {
                        $(this).remove();

                        // Update counter di header
                        $('.wishlist-qty').text(response.count);

                        // Jika wishlist kosong, tampilkan empty state
                        if ($('.product-widget').length === 0) {
                            $('.wishlist-items').html(`
                                <div class="empty-wishlist">
                                    <i class="fa fa-heart-o"></i>
                                    <p>Your wishlist is empty</p>
                                    <a href="/" class="btn btn-primary">Browse Products</a>
                                </div>
                            `);
                        }
                    });

                    showToast(response.message);
                },
                error: function(xhr) {
                    showToast('Error: ' + xhr.responseJSON.message, 'error');
                }
            });
        }

        function loadWishlistPage(url) {
            $('#ajax-loader').show();

            const fullUrl = url + (url.includes('?') ? '&' : '?') + 'sort_by=' + currentSort;

            $.ajax({
                url: fullUrl,
                type: 'GET',
                success: function(response) {
                    $('.wishlist-items').html($(response).find('.wishlist-items').html());
                    $('.pagination').html($(response).find('.pagination').html());
                    $('#ajax-loader').hide();

                    $('html, body').animate({
                        scrollTop: $('.wishlist-items').offset().top - 100
                    }, 500);
                }
            });
        }

        $(document).ready(function() {
            // Handle sort selection
            $('.drd-menu-sort a').click(function(e) {
                e.preventDefault();
                var sortBy = $(this).data('sort');

                // Update UI
                $('#currentSort').text($(this).text());

                // Load data
                loadSortedWishlist(sortBy);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                loadWishlistPage($(this).attr('href'));
            });
        });

        // function loadSortedWishlist(sortBy) {
        //     $('#ajax-loader').show();

        //     $.ajax({
        //         url: '/wishlist/sort',
        //         type: 'GET',
        //         data: {
        //             sort_by: sortBy
        //         },
        //         success: function(response) {
        //             var html = '';

        //             if(response.data.length > 0) {
        //                 console.log(response.data);
        //                 response.data.forEach(function(item) {
        //                     html += `
    //                     <div class="product-widget" data-wishlist-id="${item.id}">
    //                         <div class="product-img">
    //                             <img src="${item.produk.gambar_utama ? '/storage/' + item.produk.gambar_utama.gambar : '/img/placeholder.jpg'}" alt="${item.produk.nama}">
    //                         </div>
    //                         <div class="product-body">
    //                             <h3 class="product-name">
    //                                 <a href="/products/${item.produk.slug}">${item.produk.nama}</a>
    //                             </h3>
    //                             <h4 class="product-price">Rp ${formatNumber(item.produk.harga)}</h4>
    //                             <div class="product-btns">
    //                                 <button class="add-to-cart-btn" onclick="addToCart(${item.produk.id})">
    //                                     <i class="fa fa-shopping-cart"></i> Add to Cart
    //                                 </button>
    //                                 <button class="wishlist-remove-btn" onclick="removeFromWishlist(${item.id})">
    //                                     <i class="fa fa-trash"></i> Remove
    //                                 </button>
    //                             </div>
    //                         </div>
    //                     </div>`;
        //                 });
        //             } else {
        //                 html = `
    //                 <div class="empty-wishlist">
    //                     <i class="fa fa-heart-o"></i>
    //                     <p>Your wishlist is empty</p>
    //                     <a href="/" class="btn btn-primary">Browse Products</a>
    //                 </div>`;
        //             }

        //             $('.wishlist-items').html(html);

        //             // Update pagination
        //             if(response.links) {
        //                 $('.pagination').html(response.links);
        //             }

        //             $('#ajax-loader').hide();
        //         }
        //     });
        // }
        function loadSortedWishlist(sortBy) {
            currentSort = sortBy; // Simpan state sorting
            $('#currentSort').text($(`[data-sort="${sortBy}"]`).text());

            // Load halaman pertama dengan sorting terpilih
            loadWishlistPage('/wishlist?page=1');
        }

        // Helper function untuk format number
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
    </script>
@endpush
