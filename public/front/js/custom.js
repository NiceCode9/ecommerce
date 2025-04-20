// Fungsi untuk handle AJAX dengan loader
function ajaxRequest(config) {
    // Tampilkan loader
    $('#ajax-loader').show();
    
    return $.ajax({
        ...config,
        complete: function() {
            // Sembunyikan loader saat selesai
            $('#ajax-loader').hide();
        }
    });
}

// Fungsi untuk toggle wishlist
function toggleWishlist(productId) {
    if (!checkAuth()) return;

    ajaxRequest({
        url: '/wishlist/toggle',
        method: 'POST',
        data: {
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Update icon wishlist
            const wishlistBtn = $(`button[onclick="toggleWishlist(${productId})"]`);
            const icon = wishlistBtn.find('i');

            if (response.status === 'added') {
                icon.removeClass('fa-heart-o').addClass('fa-heart');
            } else {
                icon.removeClass('fa-heart').addClass('fa-heart-o');
            }

            // Update counter wishlist di header
            $('.wishlist-qty').text(response.count);
            showToast(response.status === 'added' ? 'Added to wishlist' : 'Removed from wishlist');
        },
        error: function (xhr) {
            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

// Fungsi untuk add to cart
// function addToCart(productId, quantity = 1) {
//     if (!checkAuth()) return;

//     ajaxRequest({
//         url: '/cart/add',
//         method: 'POST',
//         data: {
//             product_id: productId,
//             quantity: quantity,
//             _token: $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
//             // Update counter cart di header
//             $('.cart-qty').text(response.count);

//             showToast('Product added to cart');
//         },
//         error: function (xhr) {
//             showToast('Error: ' + xhr.responseJSON.message, 'error');
//         }
//     });
// }
function addToCart(productId, quantity = 1) {
    if (!checkAuth()) return;

    // Simpan state dropdown sebelum update
    var isCartOpen = $('.dropdown-toggle').parent().hasClass('open');
    
    ajaxRequest({
        url: '/cart/add',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Update counter cart di header
            $('.cart-qty').text(response.count);
            
            // Update konten dropdown
            $('.cart-dropdown').html(response.html);
            
            // Perbaiki scrollbar
            refreshCartDropdown();
            
            // Kembalikan state dropdown
            if (isCartOpen) {
                $('.dropdown-toggle').dropdown('toggle');
            }
            
            showToast('Product added to cart');
        },
        error: function(xhr) {
            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

// Fungsi khusus untuk refresh dropdown cart
function refreshCartDropdown() {
    var $dropdown = $('.dropdown-toggle').parent();
    
    // Hapus event listeners lama
    $dropdown.off('click.bs.dropdown');
    $dropdown.removeClass('open');
    
    // Init ulang dropdown
    $('.dropdown-toggle').dropdown();
    
    // Force recalculate scrollbar
    $('.cart-list').css('overflow-y', 'hidden').height();
    $('.cart-list').css('overflow-y', 'auto');
}

// Fungsi untuk remove item dari cart
function removeCartItem(cartId, event) {
    event.preventDefault();
    event.stopPropagation();
    
    if (!confirm('Are you sure to remove this item?')) return;
    var isCartOpen = $('.dropdown-toggle').parent().hasClass('open');
    
    ajaxRequest({
        url: `/cart/remove/${cartId}`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('.cart-qty').text(response.count);
            
            // Update konten dropdown
            $('.cart-dropdown').html(response.html);
            
            // Perbaiki scrollbar
            refreshCartDropdown();
            
            // Kembalikan state dropdown
            if (isCartOpen) {
                $('.dropdown-toggle').dropdown('toggle');
            }
            showToast('Item removed from cart');
        },
        error: function(xhr) {
            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

function updateQuantity(cartId, change) {
    const qtyElement = $(`[data-cart-id="${cartId}"] .qty`);
    const newQty = parseInt(qtyElement.text()) + change;
    
    if (newQty < 1) return;

    ajaxRequest({
        url: `/cart/update/${cartId}`,
        method: 'POST',
        data: {
            quantity: newQty,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            qtyElement.text(newQty);
            $(`[data-cart-id="${cartId}"] .subtotal`).text('Rp ' + response.subtotal);
            $('.cart-total').text('Rp ' + response.total);
            $('.cart-qty').text(response.total_item);
            showToast('Cart updated');
        },
        error: function(xhr) {
            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

// Fungsi untuk cek auth
function checkAuth() {
    if ($('meta[name="authenticated"]').attr('content') === 'false') {
        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
        return false;
    }
    return true;
}

// Fungsi untuk tampilkan toast notifikasi
function showToast(message, type = 'success') {
    const toast = $(`<div class="toast ${type}">${message}</div>`);
    $('body').append(toast);
    setTimeout(() => toast.remove(), 3000);
}

// // Fungsi untuk quick view
// function showQuickView(productId) {
//     $.ajax({
//         url: `/products/quick-view/${productId}`,
//         method: 'GET',
//         success: function(response) {
//             const modal = $(`
//                 <div class="modal fade" id="quickViewModal" tabindex="-1">
//                     <div class="modal-dialog modal-lg">
//                         <div class="modal-content">
//                             <div class="modal-body">
//                                 ${response}
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//             `);
            
//             $('body').append(modal);
//             $('#quickViewModal').modal('show');
            
//             // Handle saat modal ditutup
//             $('#quickViewModal').on('hidden.bs.modal', function () {
//                 $(this).remove();
//             });
//         }, error: function(xhr){
//             console.log(xhr.responseJSON.message);
//         }
//     });
// }

// Fungsi untuk quick view
function showQuickView(productId) {
    ajaxRequest({
        url: `/products/quick-view/${productId}`,
        method: 'GET',
        success: function(response) {
            const modal = $(`
                <div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${response}
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(modal);
            $('#quickViewModal').modal('show');
            
            $('#quickViewModal').on('hidden.bs.modal', function () {
                $(this).remove();
            });
        }
    });
}

// Fungsi untuk ganti gambar utama
function changeMainImage(src) {
    $('.main-image').attr('src', src);
}

// Update counter wishlist dan cart saat load halaman
$(document).ready(function () {
    if ($('meta[name="authenticated"]').attr('content') === 'true') {
        // Anda bisa tambahkan AJAX untuk get jumlah wishlist dan cart jika diperlukan
    }

    // Event listener untuk tombol quick view
    $(document).on('click', '.quick-view', function(e) {
        e.preventDefault();
        const productId = $(this).closest('.product').data('product-id');
        showQuickView(productId);
    });
});
