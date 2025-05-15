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
function searchAddress() {
    const keyword = $('#addressSearchInput').val().trim();
    if (keyword.length < 3) {
        showToast('Masukkan minimal 3 karakter', 'error');
        return;
    }

    $('#searchLoading').show();
    $('#addressResults').empty();
    $('#noResults').hide();

    ajaxRequest({
        url: '/get-wilayah',
        type: 'GET',
        data: { keyword: keyword },
        success: function (response) {
            $('#searchLoading').hide();

            if (response.data && response.data.length > 0) {
                const $tbody = $('#addressResults');
                response.data.forEach(address => {
                    $tbody.append(`
                        <tr>
                            <td>
                                <strong>${address.label || address.district_name}</strong><br>
                                <small class="text-muted">
                                    ${address.subdistrict_name}, ${address.city_name}, ${address.province_name}
                                </small>
                            </td>
                            <td>${address.zip_code || '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary select-address"
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
            $('#searchLoading').hide();
            showToast('Terjadi kesalahan saat mencari alamat', 'error');
        }
    });
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

// function cari alamat untuk halaman checkout
function getAlamatCheckout() {
    const $alamatSelect = $('#alamatSelect');
    $alamatSelect.html('<option value="">--pilih alamat--</option>');

    $.ajax({
        type: "GET",
        url: "/alamat/get-alamat-pelanggan",
        dataType: "json",
        success: function (response) {
            let options = '<option value="">--pilih alamat--</option>';
            console.log(response);

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

            // Trigger change jika ada alamat utama
            const utama = $alamatSelect.find('option[data-label*="(Alamat Utama)"]');
            if (utama.length > 0) {
                utama.prop('selected', true).trigger('change');
            }
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
        url: '/get-wilayah',
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

// Fungsi untuk memuat daftar alamat di halaman profile
function loadAlamatList() {
    ajaxRequest({
        url: '/alamat/get-alamat-pelanggan',
        method: 'GET',
        success: function (response) {
            renderAlamatList(response);
        },
        error: function (xhr) {
            showToast('Gagal memuat daftar alamat', 'error');
        }
    });
}

// Fungsi untuk render daftar alamat
function renderAlamatList(alamats) {
    const $alamatContainer = $('#alamat-list-container');
    $alamatContainer.empty();

    if (alamats.length === 0) {
        $alamatContainer.html(`
            <div class="text-center py-4">
                <i class="fa fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">Anda belum memiliki alamat. Tambahkan alamat sekarang.</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAddressModal">
                    <i class="fa fa-plus mr-1"></i> Tambah Alamat
                </button>
            </div>
        `);
        return;
    }

    let html = '<div class="row">';

    alamats.forEach(alamat => {
        html += `
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm ${alamat.is_utama ? 'border-primary' : ''}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            ${alamat.is_utama ?
                '<span class="badge badge-primary">Utama</span>' :
                '<span class="badge badge-secondary">Tambahan</span>'}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button class="dropdown-item" data-toggle="modal"
                                        data-target="#editAddressModal"
                                        onclick="prepareEditAddress(${alamat.id})">
                                        <i class="fa fa-edit mr-2"></i> Edit
                                    </button>
                                    <button class="dropdown-item" onclick="deleteAddress(${alamat.id})">
                                        <i class="fa fa-trash mr-2"></i> Hapus
                                    </button>
                                    ${!alamat.is_utama ? `
                                    <button class="dropdown-item" onclick="setMainAddress(${alamat.id})">
                                        <i class="fa fa-star mr-2"></i> Jadikan Utama
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title">${alamat.nama_penerima}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fa fa-phone-alt mr-2"></i>${alamat.nomor_telepon}
                        </p>
                        <p class="card-text mb-1">${alamat.alamat_lengkap}</p>
                        <p class="card-text text-muted small mb-1">
                            ${alamat.kelurahan}, ${alamat.kecamatan}, ${alamat.kota}, ${alamat.provinsi}
                        </p>
                        <p class="card-text text-muted small">
                            <i class="fa fa-map-pin mr-1"></i> Kode Pos: ${alamat.kode_pos}
                        </p>
                        ${alamat.catatan ? `
                        <div class="alert alert-light small mt-3 mb-0">
                            <i class="fa fa-sticky-note mr-2"></i> ${alamat.catatan}
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });

    html += '</div>';
    $alamatContainer.html(html);
}

// Fungsi untuk menyiapkan edit alamat
function prepareEditAddress(id) {
    ajaxRequest({
        url: `/alamat/${id}`,
        method: 'GET',
        success: function (response) {
            $('#editAddressModal input[name="id"]').val(response.id);
            $('#editAddressModal input[name="nama_penerima"]').val(response.nama_penerima);
            $('#editAddressModal input[name="nomor_telepon"]').val(response.nomor_telepon);
            $('#editAddressModal textarea[name="alamat_lengkap"]').val(response.alamat_lengkap);
            $('#editAddressModal input[name="provinsi"]').val(response.provinsi);
            $('#editAddressModal input[name="kota"]').val(response.kota);
            $('#editAddressModal input[name="kecamatan"]').val(response.kecamatan);
            $('#editAddressModal input[name="kelurahan"]').val(response.kelurahan);
            $('#editAddressModal input[name="kode_pos"]').val(response.kode_pos);
            $('#editAddressModal textarea[name="catatan"]').val(response.catatan);
            $('#editAddressModal input[name="is_utama"]').prop('checked', response.is_utama);
            $('#editAddressModal input[name="api_id"]').val(response.api_id);

            // Set value untuk pencarian alamat
            $('#editAddressModal input[name="cari-alamat"]').val(
                `${response.kelurahan}, ${response.kecamatan}, ${response.kota}, ${response.provinsi}`
            );
        }
    });
}

// Fungsi untuk menghapus alamat
function deleteAddress(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus alamat ini?')) return;

    ajaxRequest({
        url: `/alamat/${id}`,
        method: 'DELETE',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            showToast(response.message);
            loadAlamatList();

            // Tutup semua modal yang terbuka
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
        },
        error: function (xhr) {
            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

// Fungsi untuk set alamat utama
function setMainAddress(id) {
    ajaxRequest({
        url: `/alamat/${id}/set-utama`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            showToast(response.message);
            loadAlamatList();
        },
        error: function (xhr) {
            console.log(xhr.responseJSON.message);

            showToast('Error: ' + xhr.responseJSON.message, 'error');
        }
    });
}

// Update counter wishlist dan cart saat load halaman
$(document).ready(function () {
    // if ($('meta[name="authenticated"]').attr('content') === 'true') {
    //     // Anda bisa tambahkan AJAX untuk get jumlah wishlist dan cart jika diperlukan
    // }

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
        // $('#addAddressModal').modal('hide'); // Sembunyikan sementara modal parent
        $('#searchAddressModal').modal('show');
    });

    // Saat modal pencarian ditutup
    // $('#searchAddressModal').on('hidden.bs.modal', function () {
    //     $('#addAddressModal').modal('show'); // Tampilkan kembali modal parent
    // });
    $('#searchAddressBtn').click(searchAddress);
    $('#addressSearchInput').keypress(function (e) {
        if (e.which == 13) { // Enter key
            searchAddress();
        }
    });

    // Pilih alamat dari hasil pencarian
    // $(document).on('click', '.select-address', function () {
    //     $('#searchAddressModal').modal('hide');
    //     const address = $(this).data('address');

    //     // Isi field-field di form utama
    //     $('input[name="nama_penerima"]').val(''); // Reset dulu
    //     $('input[name="nomor_telepon"]').val(''); // Reset dulu
    //     $('input[name="provinsi"]').val(address.province_name);
    //     $('input[name="kota"]').val(address.city_name);
    //     $('input[name="kecamatan"]').val(address.subdistrict_name);
    //     $('input[name="kelurahan"]').val(address
    //         .district_name); // Catatan: district_name biasanya nama kecamatan
    //     $('input[name="kode_pos"]').val(address.zip_code);
    //     // $('textarea[name="alamat_lengkap"]').val(address.label);
    //     $('input[name="label"]').val(address.label);
    //     $('input[name="api_id"]').val(address.id); // Simpan ID dari API

    //     // Update tampilan
    //     $('input[name="cari-alamat"]').val(address.label);

    //     // Tutup modal pencarian
    //     $('#searchAddressModal').modal('hide');
    // });

    // Pilih alamat dari hasil pencarian
    $(document).on('click', '.select-address', function () {
        const address = $(this).data('address');
        const $activeForm = $('#searchAddressModal').data('active-form');

        // Isi field-field di form yang aktif (tambah/edit)
        $($activeForm + ' input[name="provinsi"]').val(address.province_name);
        $($activeForm + ' input[name="kota"]').val(address.city_name);
        $($activeForm + ' input[name="kecamatan"]').val(address.subdistrict_name);
        $($activeForm + ' input[name="kelurahan"]').val(address.district_name);
        $($activeForm + ' input[name="kode_pos"]').val(address.zip_code);
        $($activeForm + ' input[name="api_id"]').val(address.id);
        $($activeForm + ' input[name="label"]').val(address.label);



        // Update tampilan field pencarian
        $($activeForm + ' input[name="cari-alamat"]').val(
            `${address.district_name}, ${address.subdistrict_name}, ${address.city_name}, ${address.province_name}`
        );

        $('#searchAddressModal').modal('hide');
    });

    // Set active form saat modal dibuka
    $('#addAddressModal').on('show.bs.modal', function () {
        $('#searchAddressModal').data('active-form', '#addressForm');
    });

    $('#editAddressModal').on('show.bs.modal', function () {
        $('#searchAddressModal').data('active-form', '#editAddressForm ');
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

                    // getAlamatCheckout();
                    // Panggil fungsi refresh yang sesuai berdasarkan halaman
                    if (window.location.pathname.includes('checkout')) {
                        getAlamatCheckout();
                    } else {
                        loadAlamatList();
                    }
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
            }
        });
    });
});
