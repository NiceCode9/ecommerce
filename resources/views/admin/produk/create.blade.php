@extends('admin.layouts.app', ['title' => 'Tambah Produk'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk"
                                class="form-control @error('nama_produk') is-invalid @enderror"
                                value="{{ old('nama_produk') }}">
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug"
                                class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku"
                                class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" name="harga" id="harga"
                                class="form-control @error('harga') is-invalid @enderror" step="0.01"
                                value="{{ old('harga') }}">
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="diskon">Diskon (%)</label>
                            <input type="number" name="diskon" id="diskon"
                                class="form-control @error('diskon') is-invalid @enderror" step="0.01"
                                value="{{ old('diskon') }}">
                            @error('diskon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" id="stok"
                                class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok') }}">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="berat">Berat (kg)</label>
                            <input type="number" name="berat" id="berat"
                                class="form-control @error('berat') is-invalid @enderror" step="0.01"
                                value="{{ old('berat') }}">
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select name="kondisi" id="kondisi"
                                class="form-control @error('kondisi') is-invalid @enderror">
                                <option value="baru" {{ old('kondisi') == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="bekas" {{ old('kondisi') == 'bekas' ? 'selected' : '' }}>Bekas</option>
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
                                        {{ old('brand_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
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
                                        {{ old('kategori_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}
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
                                        {{ old('socket_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
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
                                        {{ old('mobo_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
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
                                value="{{ old('garansi_bulan') }}">
                            @error('garansi_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="is_aktif">Aktif</label>
                            <select name="is_aktif" id="is_aktif"
                                class="form-control @error('is_aktif') is-invalid @enderror">
                                <option value="1" {{ old('is_aktif') == '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('is_aktif') == '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                            @error('is_aktif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <div class="row" id="image-preview"></div>
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
                                                <tr>
                                                    <td>
                                                        <input type="text" name="nama_spek[]"
                                                            class="form-control @error('nama_spek.*') is-invalid @enderror">
                                                        @error('nama_spek.*')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text" name="nilai[]"
                                                            class="form-control @error('nilai.*') is-invalid @enderror">
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
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                } else if (kategoriTipe === 'memory') {
                    $('#socket_id').closest('.form-group').hide();
                    $('#mobo_id').prop('disabled', false);
                    $('#mobo_id').closest('.form-group').show();
                } else {
                    $('#socket_id').closest('.form-group').hide();
                    // $('#mobo_id').prop('disabled', false);
                    $('#mobo_id').closest('.form-group').hide();
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
                // Reorder remaining images
                $('#gambar-table tbody tr').each((index, row) => {
                    $(row).find('input[name="urutan[]"]').val(index + 1);
                });
            });
        });
    </script>
@endpush
