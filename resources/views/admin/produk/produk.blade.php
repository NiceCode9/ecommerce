@extends('admin.layouts.app', ['title' => 'Data Produk'])

@push('css')
    <style>
        .carousel-inner {
            min-height: 300px;
        }

        .carousel-item {
            cursor: move;
        }

        .ui-sortable-helper {
            transform: rotate(5deg);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary btn-sm mb-3 float-right">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <div class="table-responsive">
                        <table class="table datatable text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Brand</th>
                                    <th>Garansi</th>
                                    <th>Harga</th>
                                    <th>Harga Diskon</th>
                                    <th>Stok</th>
                                    <th>Diskon</th>
                                    <th>Berat</th>
                                    <th>Kondisi</th>
                                    <th>Is Aktif</th>
                                    <th>Gambar Produk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->nama }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->kategori->nama }}</td>
                                        <td>{{ $product->brand->nama }}</td>
                                        <td>{{ $product->garani }} Bulan</td>
                                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($product->harga_setelah_diskon, 0, ',', '.') }}</td>
                                        <td>{{ $product->stok }}</td>
                                        <td>{{ $product->diskon }} %</td>
                                        <td>{{ $product->berat }}</td>
                                        <td>{{ $product->kondisi }}</td>
                                        <td>
                                            @if ($product->is_aktif)
                                                <i class="fas fa-check"></i>
                                            @else
                                                <i class="fas fa-times"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm btn-preview-gambar" title="Gambar Produk"
                                                data-id="{{ $product->id }}" data-toggle="modal"
                                                data-target="#gambarModal">
                                                <i class="fas fa-images"></i> Gambar ({{ $product->gambar->count() }})
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.produk.edit', $product->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit Produk">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-delete"
                                                title="Hapus Produk" data-id="{{ $product->id }}">
                                                <i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="gambarModal" tabindex="-1" role="dialog" aria-labelledby="gambarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gambarModalLabel">Gambar Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form id="form-upload-gambar" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="produk_id" id="produk_id_upload">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar_tambahan" name="gambar"
                                    accept="image/*">
                                <label class="custom-file-label" for="gambar_tambahan">Pilih gambar</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </form>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators" id="carousel-indicators">
                            <!-- Indicators akan diisi oleh JS -->
                        </ol>
                        <div class="carousel-inner" id="carousel-inner">
                            <!-- Gambar akan diisi oleh JS -->
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-set-utama" style="display:none;">
                        <i class="fas fa-check-circle"></i> Set Sebagai Utama
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            let currentProductId = null;
            let currentGambarId = null;

            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                var url = "{{ route('admin.produk.destroy', ':produk') }}".replace(':produk', id);
                console.log(url);

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log(response);
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                console.log(xhr.responseJSON.message);

                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message,
                                    'error'
                                );
                            }
                        });
                    }
                })
            });

            // Menangani preview gambar
            $(document).on('click', '.btn-preview-gambar', function() {
                currentProductId = $(this).data('id');
                loadGambar(currentProductId);
            });

            // Inisialisasi sortable untuk gambar
            function initSortable() {
                $('#carousel-inner').sortable({
                    items: '.carousel-item',
                    cursor: 'move',
                    opacity: 0.7,
                    update: function() {
                        const urutan = [];
                        $('#carousel-inner .carousel-item').each(function(index) {
                            urutan.push({
                                id: $(this).data('gambar-id'),
                                urutan: index + 1
                            });
                        });

                        $.ajax({
                            url: '/admin/produk/gambar/update-urutan',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                urutan: urutan
                            },
                            success: function(response) {
                                // Optional: Tampilkan notifikasi sukses
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Gagal mengupdate urutan gambar',
                                    'error'
                                );
                            }
                        });
                    }
                }).disableSelection();
            }

            // Fungsi untuk memuat gambar
            function loadGambar(productId) {
                // Kosongkan carousel
                $('#carousel-indicators').empty();
                $('#carousel-inner').empty();
                $('#btn-set-utama').hide();

                // Tampilkan loading
                $('#carousel-inner').html(
                    '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');

                // Ambil data gambar via AJAX
                const url = "{{ route('admin.produk.getgambar', ':id') }}".replace(':id', productId);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Kosongkan lagi setelah loading
                        $('#carousel-indicators').empty();
                        $('#carousel-inner').empty();

                        // Isi carousel dengan gambar
                        response.gambar.forEach((gambar, index) => {
                            // Tambahkan indicator
                            $('#carousel-indicators').append(
                                `<li data-target="#carouselExampleIndicators"
                            data-slide-to="${index}"
                            ${index === 0 ? 'class="active"' : ''}></li>`
                            );

                            // Tambahkan gambar
                            $('#carousel-inner').append(`
                                <div class="carousel-item ${index === 0 ? 'active' : ''}" data-gambar-id="${gambar.id}">
                                    <img class="d-block w-100" src="${gambar.path}"
                                        alt="Gambar produk ${index + 1}">
                                    <div class="carousel-caption d-none d-md-block">
                                        <p>Gambar ${index + 1} ${gambar.is_utama ? '(Utama)' : ''}</p>
                                        <button class="btn btn-danger btn-sm btn-hapus-gambar"
                                                data-id="${gambar.id}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            `);
                        });

                        // Jika tidak ada gambar
                        if (response.gambar.length === 0) {
                            $('#carousel-inner').html(
                                '<div class="text-center py-5">Tidak ada gambar</div>');
                        }

                        initSortable();
                    },
                    error: function(xhr) {
                        $('#carousel-inner').html(
                            '<div class="text-center py-5 text-danger">Gagal memuat gambar</div>');
                    }
                });
            }

            // Ketika carousel slide berubah
            $('#carouselExampleIndicators').on('slid.bs.carousel', function() {
                const activeItem = $(this).find('.carousel-item.active');
                currentGambarId = activeItem.data('gambar-id');
                const isUtama = activeItem.find('.carousel-caption').text().includes('(Utama)');

                if (isUtama) {
                    $('#btn-set-utama').hide();
                } else {
                    $('#btn-set-utama').show();
                }
            });

            // Hapus gambar
            $(document).on('click', '.btn-hapus-gambar', function(e) {
                e.stopPropagation();
                const gambarId = $(this).data('id');
                const urlDeleteGambar = "{{ route('admin.produk.destroyGambar', ':id') }}".replace(':id',
                    gambarId);

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Gambar ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: urlDeleteGambar,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    loadGambar(currentProductId);
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Set gambar utama
            $('#btn-set-utama').on('click', function() {
                if (!currentGambarId) repathturn;

                let url = "{{ route('admin.produk.gambar.set-utama', ':id') }}".replace(':id',
                    currentGambarId);
                console.log(url);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(() => {
                            loadGambar(currentProductId);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message,
                            'error'
                        );
                    }
                });
            });

            // Ketika modal dibuka
            $('#gambarModal').on('show.bs.modal', function(e) {
                const productId = $(e.relatedTarget).data('id');
                $('#produk_id_upload').val(productId);
            });

            // Upload gambar tambahan
            $('#form-upload-gambar').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.produk.gambar.upload') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#form-upload-gambar button[type="submit"]').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin"></i> Uploading...');
                    },
                    success: function(response) {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(() => {
                            loadGambar($('#produk_id_upload').val());
                            $('#form-upload-gambar')[0].reset();
                            $('.custom-file-label').text('Pilih gambar');
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Gagal mengupload gambar',
                            'error'
                        );
                    },
                    complete: function() {
                        $('#form-upload-gambar button[type="submit"]').prop('disabled', false)
                            .html('<i class="fas fa-upload"></i> Upload');
                    }
                });
            });

            // Update label file input
            $('#gambar_tambahan').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').text(fileName || 'Pilih gambar');
            });
        });
    </script>
@endpush
