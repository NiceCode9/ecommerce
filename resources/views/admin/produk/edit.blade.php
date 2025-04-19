@extends('admin.layouts.app', ['title' => 'Edit Produk'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk"
                                class="form-control @error('nama_produk') is-invalid @enderror"
                                value="{{ old('nama_produk', $produk->nama) }}">
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $produk->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku"
                                class="form-control @error('sku') is-invalid @enderror"
                                value="{{ old('sku', $produk->sku) }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" name="harga" id="harga"
                                class="form-control @error('harga') is-invalid @enderror" step="0.01"
                                value="{{ old('harga', $produk->harga) }}">
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="diskon">Diskon (%)</label>
                            <input type="number" name="diskon" id="diskon"
                                class="form-control @error('diskon') is-invalid @enderror" step="0.01"
                                value="{{ old('diskon', $produk->diskon) }}">
                            @error('diskon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" id="stok"
                                class="form-control @error('stok') is-invalid @enderror"
                                value="{{ old('stok', $produk->stok) }}">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="berat">Berat (gram)</label>
                            <input type="number" name="berat" id="berat"
                                class="form-control @error('berat') is-invalid @enderror" step="0.01"
                                value="{{ old('berat', $produk->berat) }}">
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select name="kondisi" id="kondisi"
                                class="form-control @error('kondisi') is-invalid @enderror">
                                <option value="baru" {{ old('kondisi', $produk->kondisi) == 'baru' ? 'selected' : '' }}>
                                    Baru</option>
                                <option value="bekas" {{ old('kondisi', $produk->kondisi) == 'bekas' ? 'selected' : '' }}>
                                    Bekas</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Brand</label>
                            <select name="brand_id" id="brand_id"
                                class="form-control @error('brand_id') is-invalid @enderror">
                                <option value="">--Pilih Brand--</option>
                                @foreach ($brands as $item)
                                    <option value="{{ $item->id }}" data-ispro="{{ $item->is_processor }}"
                                        {{ old('brand_id', $produk->brand_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" id="kategori_id"
                                class="form-control @error('kategori_id') is-invalid @enderror">
                                <option value="">--Pilih Kategori--</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('kategori_id', $produk->kategori_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="socket_id">Socket</label>
                            <select name="socket_id" id="socket_id"
                                class="form-control @error('socket_id') is-invalid @enderror">
                                <option value="">--Pilih Socket--</option>
                                @foreach ($sockets as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('socket_id', $produk->socket_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('socket_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mobo_id">Motherboard</label>
                            <select name="mobo_id" id="mobo_id"
                                class="form-control @error('mobo_id') is-invalid @enderror">
                                <option value="">--Pilih Mobo--</option>
                                @foreach ($mobos as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('mobo_id', $produk->mobo_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('mobo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="garansi_bulan">Garansi (bulan)</label>
                            <input type="number" name="garansi_bulan" id="garansi_bulan"
                                class="form-control @error('garansi_bulan') is-invalid @enderror"
                                value="{{ old('garansi_bulan', $produk->garansi_bulan) }}">
                            @error('garansi_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="is_aktif">Aktif</label>
                            <select name="is_aktif" id="is_aktif"
                                class="form-control @error('is_aktif') is-invalid @enderror">
                                <option value="1" {{ old('is_aktif', $produk->is_aktif) == '1' ? 'selected' : '' }}>
                                    Ya</option>
                                <option value="0" {{ old('is_aktif', $produk->is_aktif) == '0' ? 'selected' : '' }}>
                                    Tidak</option>
                            </select>
                            @error('is_aktif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar yang sudah ada -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Gambar Produk (Yang Sudah Ada)</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="existing-image-table">
                                        <thead>
                                            <tr>
                                                <th>Preview</th>
                                                <th>Gambar Utama</th>
                                                <th>Urutan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produk->gambar as $gambar)
                                                <tr>
                                                    <td>
                                                        <img src="{{ asset('storage/' . $gambar->gambar) }}"
                                                            height="50">
                                                    </td>
                                                    <td>
                                                        <input type="radio" name="is_utama_existing"
                                                            value="{{ $gambar->id }}"
                                                            {{ $gambar->is_utama ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="urutan_existing[{{ $gambar->id }}]"
                                                            class="form-control" value="{{ $gambar->urutan }}">
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-existing-image"
                                                            data-id="{{ $gambar->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Gambar baru -->
                        <div class="form-group">
                            <label for="gambar">Tambah Gambar Baru</label>
                            <input type="file" name="gambar[]" id="gambar"
                                class="form-control @error('gambar.*') is-invalid @enderror" multiple accept="image/*">
                            @error('gambar.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="gambar-table">
                                        <thead>
                                            <tr>
                                                <th>Preview</th>
                                                <th>Gambar Utama</th>
                                                <th>Urutan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('gambar_preview'))
                                                @foreach (old('gambar_preview') as $key => $preview)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ $preview }}" height="50">
                                                            <input type="hidden" name="gambar_preview[]"
                                                                value="{{ $preview }}">
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="is_utama"
                                                                value="{{ $key }}"
                                                                {{ old('is_utama') == $key ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="urutan[]"
                                                                class="form-control @error('urutan.*') is-invalid @enderror"
                                                                value="{{ old('urutan.' . $key) }}">
                                                            @error('urutan.*')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-image">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Spesifikasi -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <button type="button" class="btn btn-info btn-sm mt-3 mb-1 add-spesifikasi">
                                        <i class="fas fa-plus"></i> Tambah Spesifikasi
                                    </button>
                                    <table class="table table-bordered text-nowrap" style="width: 100%"
                                        id="spesifikasi-table">
                                        <thead>
                                            <tr>
                                                <th>Jenis Spesifikasi</th>
                                                <th>Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('nama_spek'))
                                                @foreach (old('nama_spek') as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="nama_spek[]"
                                                                class="form-control @error('nama_spek.*') is-invalid @enderror"
                                                                value="{{ $value }}">
                                                            @error('nama_spek.*')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" name="nilai[]"
                                                                class="form-control @error('nilai.*') is-invalid @enderror"
                                                                value="{{ old('nilai.' . $key) }}">
                                                            @error('nilai.*')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-spesifikasi">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($produk->spesifikasi as $spesifikasi)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="nama_spek[]"
                                                                class="form-control @error('nama_spek.*') is-invalid @enderror"
                                                                value="{{ $spesifikasi->nama_spek }}">
                                                            @error('nama_spek.*')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" name="nilai[]"
                                                                class="form-control @error('nilai.*') is-invalid @enderror"
                                                                value="{{ $spesifikasi->nilai }}">
                                                            @error('nilai.*')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-spesifikasi">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateFieldVisibility(kategoriTipe) {
                $('#socket_id').prop('disabled', true);
                $('#mobo_id').prop('disabled', true);
                // $('#socket_id').closest('.form-group').hide();
                // $('#mobo_id').closest('.form-group').hide();

                if (kategoriTipe === 'processor' || kategoriTipe === 'motherboard') {
                    $('#socket_id').prop('disabled', false);
                    $('#socket_id').closest('.form-group').show();
                    $('#mobo_id').closest('.form-group').hide();
                } else {
                    $('#socket_id').closest('.form-group').hide();
                    $('#mobo_id').prop('disabled', false);
                    $('#mobo_id').closest('.form-group').show();
                }
            }

            // Simpan data tipe kategori
            const kategoriData = {
                @foreach ($kategori as $item)
                    {{ $item->id }}: '{{ $item->tipe }}',
                @endforeach
            };

            // Panggil saat halaman dimuat untuk kategori yang sudah dipilih
            if ($('#kategori_id').val()) {
                updateFieldVisibility(kategoriData[$('#kategori_id').val()]);
            }

            // Tambahkan event listener untuk perubahan kategori
            $('#kategori_id').on('change', function() {
                const kategoriId = $(this).val();
                if (kategoriId && kategoriData[kategoriId]) {
                    updateFieldVisibility(kategoriData[kategoriId]);
                } else {
                    // Default jika kategori tidak dipilih
                    $('#socket_id').prop('disabled', true);
                    $('#mobo_id').prop('disabled', true);
                }
            });

            $('.add-spesifikasi').on('click', function() {
                let row = `
                    <tr>
                        <td>
                            <input type="text" name="nama_spek[]" class="form-control @error('nama_spek.*') is-invalid @enderror">
                            @error('nama_spek.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="text" name="nilai[]" class="form-control @error('nilai.*') is-invalid @enderror">
                            @error('nilai.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-spesifikasi">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#spesifikasi-table tbody').append(row);
            });

            $(document).on('click', '.remove-spesifikasi', function() {
                $(this).closest('tr').remove();
            });

            $('#slug').on('input', function() {
                let slug = $(this).val();
                slug = slug.replace(/[^a-z0-9]+/g, '-').toLowerCase();
                $(this).val(slug);
            });
            $('#harga').on('input', function() {
                let harga = $(this).val();
                harga = harga.replace(/[^0-9]/g, '');
                $(this).val(harga);
            });
            $('#diskon').on('input', function() {
                let diskon = $(this).val();
                diskon = diskon.replace(/[^0-9]/g, '');
                $(this).val(diskon);
            });

            $('#gambar').on('change', function(e) {
                let files = e.target.files;

                Array.from(files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let row = `
                            <tr>
                                <td>
                                    <img src="${e.target.result}" height="50">
                                    <input type="hidden" name="gambar_preview[]" value="${e.target.result}">
                                    <input type="file" name="gambar[]"
                                        class="@error('gambar.*') is-invalid @enderror" hidden>
                                    @error('gambar.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="radio" name="is_utama" value="${$('#gambar-table tbody tr').length}"
                                        ${$('#gambar-table tbody tr').length === 0 ? 'checked' : ''}>
                                </td>
                                <td>
                                    <input type="number" name="urutan[]" class="form-control"
                                        value="${$('#gambar-table tbody tr').length + 1}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-image">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('#gambar-table tbody').append(row);
                    }
                    reader.readAsDataURL(file);
                });
            });

            $(document).on('click', '.remove-image', function() {
                $(this).closest('tr').remove();
                $('#gambar-table tbody tr').each((index, row) => {
                    $(row).find('input[name="urutan[]"]').val(index + 1);
                });
            });

            // Hapus gambar yang sudah ada
            $(document).on('click', '.remove-existing-image', function() {
                const id = $(this).data('id');
                const url = "{{ route('admin.produk.destroyGambar', ':id') }}".replace(':id', id);
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Gambar ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/produk/gambar/' + id,
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
                                    location.reload();
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
        });
    </script>
@endpush
