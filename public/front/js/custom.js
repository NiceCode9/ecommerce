// Fungsi untuk toggle wishlist
function toggleWishlist(productId) {
    if (!checkAuth()) return;

    $.ajax({
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

            // Tampilkan notifikasi
            showToast(response.status === 'added' ? 'Added to wishlist' : 'Removed from wishlist');
        },
        error: function (xhr) {
            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

// Fungsi untuk add to cart
function addToCart(productId, quantity = 1) {
    if (!checkAuth()) return;

    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Update counter cart di header
            $('.cart-qty').text(response.count);

            // Tampilkan notifikasi
            showToast('Product added to cart');
        },
        error: function (xhr) {
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

// Update counter wishlist dan cart saat load halaman
$(document).ready(function () {
    if ($('meta[name="authenticated"]').attr('content') === 'true') {
        // Anda bisa tambahkan AJAX untuk get jumlah wishlist dan cart jika diperlukan
    }
});
