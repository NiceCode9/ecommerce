// Fungsi untuk handle AJAX dengan loader
function ajaxRequest(config) {
    // Tampilkan loader
    $('#ajax-loader').show();

    return $.ajax({
        ...config,
        complete: function () {
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
        success: function (response) {
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
        error: function (xhr) {
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
        success: function (response) {
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
        error: function (xhr) {
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
        success: function (response) {
            qtyElement.text(newQty);
            $(`[data-cart-id="${cartId}"] .subtotal`).text('Rp ' + response.subtotal);
            $('.cart-total').text('Rp ' + response.total);
            $('.cart-qty').text(response.total_item);
            showToast('Cart updated');
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
        success: function (response) {
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

// function cari alamat
function getAlamatPengguna() {
    const $alamatSelect = $('select[name=alamat_id]');
    $alamatSelect.html('<option value="">--pilih alamat--</option>');

    $.ajax({
        type: "GET",
        url: "/alamat/get-alamat-pelanggan", // Pastikan URL sesuai dengan route Anda
        dataType: "json",
        success: function (response) {
            let options = '<option value="">--pilih alamat--</option>';
            $.each(response, function (i, val) {
                options += `
                    <option value="${val.id}"
                        data-city-id="${val.api_id}"
                        data-alamat-lengkap="${val.alamat_lengkap}"
                        data-nama-penerima="${val.nama_penerima}"
                        data-label="${val.label}"
                        data-no-telp="${val.nomor_telepon}">
                        ${val.label} ${val.is_utama ? '- (Alamat Utama)' : ''}
                    </option>
                `;
            });
            $alamatSelect.html(options);
        },
        error: function (xhr) {
            console.error('Error fetching addresses:', xhr.responseJSON.message);
            showToast('Gagal memuat alamat pengguna', 'error');
        }
    });
}
function searchAddress() {
    const keyword = $('#addressSearchInput').val().trim();
    if (keyword.length < 3) {
        alert('Masukkan minimal 3 karakter');
        return;
    }

    $('#searchLoading').show();
    $('#addressResults').empty();
    $('#noResults').hide();

    $.ajax({
        url: '/get-wilayah', // Sesuaikan dengan route Anda
        type: 'GET',
        data: {
            keyword: keyword
        },
        success: function (response) {
            console.log(response);

            $('#searchLoading').hide();

            if (response.data.data.length > 0) {
                response.data.data.forEach(address => {
                    $('#addressResults').append(`
                            <tr>
                                <td>
                                    ${address.label}<br>
                                    <small class="text-muted">
                                        ${address.subdistrict_name}, ${address.district_name},
                                        ${address.city_name}, ${address.province_name}
                                    </small>
                                </td>
                                <td>${address.zip_code}</td>
                                <td>
                                    <button class="btn btn-xs btn-primary select-address"
                                        data-address='${JSON.stringify(address)}'>
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                        `);
                });
            } else {
                $('#noResults').show();
            }
        },
        error: function (xhr) {
            console.log(xhr.responseJSON.message);

            $('#searchLoading').hide();
            showToast('Terjadi kesalahan saat mencari alamat');
        }
    });
}

// Update counter wishlist dan cart saat load halaman
$(document).ready(function () {
    if ($('meta[name="authenticated"]').attr('content') === 'true') {
        // Anda bisa tambahkan AJAX untuk get jumlah wishlist dan cart jika diperlukan
    }

    // Event listener untuk tombol quick view
    $(document).on('click', '.quick-view', function (e) {
        e.preventDefault();
        const productId = $(this).closest('.product').data('product-id');
        showQuickView(productId);
    });

    // Handle pencarian alamat
    $(document).on('click', 'input[name="cari-alamat"]', function () {
        $('#searchAddressModal').modal('show');
    });
    $('#searchAddressModal').on('show.bs.modal', function () {
        $('.modal-backdrop').not(':first').remove();
    });

    $('#addressSearchTrigger').click(function () {
        $('#addAddressModal').modal('hide'); // Sembunyikan sementara modal parent
        $('#searchAddressModal').modal('show');
    });

    // Saat modal pencarian ditutup
    $('#searchAddressModal').on('hidden.bs.modal', function () {
        $('#addAddressModal').modal('show'); // Tampilkan kembali modal parent
    });
    $('#searchAddressBtn').click(searchAddress);
    $('#addressSearchInput').keypress(function (e) {
        if (e.which == 13) { // Enter key
            searchAddress();
        }
    });
    // Pilih alamat dari hasil pencarian
    $(document).on('click', '.select-address', function () {
        $('#searchAddressModal').modal('hide');
        const address = $(this).data('address');

        // Isi field-field di form utama
        $('input[name="nama_penerima"]').val(''); // Reset dulu
        $('input[name="nomor_telepon"]').val(''); // Reset dulu
        $('input[name="provinsi"]').val(address.province_name);
        $('input[name="kota"]').val(address.city_name);
        $('input[name="kecamatan"]').val(address.subdistrict_name);
        $('input[name="kelurahan"]').val(address
            .district_name); // Catatan: district_name biasanya nama kecamatan
        $('input[name="kode_pos"]').val(address.zip_code);
        // $('textarea[name="alamat_lengkap"]').val(address.label);
        $('input[name="label"]').val(address.label);
        $('input[name="api_id"]').val(address.id); // Simpan ID dari API

        // Update tampilan
        $('input[name="cari-alamat"]').val(address.label);

        // Tutup modal pencarian
        $('#searchAddressModal').modal('hide');
    });

    // Submit form alamat via AJAX
    $('#addressForm').submit(function (e) {
        e.preventDefault();

        ajaxRequest({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    $('#addAddressModal').modal('hide');
                    showToast('Alamat berhasil ditambahkan');

                    getAlamatPengguna();
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
            }
        });
    });
});
