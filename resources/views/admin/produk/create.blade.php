@extends('admin.layouts.app', ['title' => 'Tambah Produk'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Produk</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                value="{{ old('slug') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control"
                                value="{{ old('sku') }}">
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control" step="0.01"
                                value="{{ old('harga') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="diskon">Diskon (%)</label>
                            <input type="number" name="diskon" id="diskon" class="form-control" step="0.01"
                                value="{{ old('diskon') }}">
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" id="stok" class="form-control"
                                value="{{ old('stok') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="berat">Berat (gram)</label>
                            <input type="number" name="berat" id="berat" class="form-control" step="0.01"
                                value="{{ old('berat') }}">
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select name="kondisi" id="kondisi" class="form-control">
                                <option value="baru" {{ old('kondisi') == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="bekas" {{ old('kondisi') == 'bekas' ? 'selected' : '' }}>Bekas</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                @foreach ($brands as $item)
                                    <option value="{{ $item->id }}" data-ispro="{{ $item->is_processor }}"
                                        {{ old('brand_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('kategori_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="socket_id">Socket</label>
                            <select name="socket_id" id="socket_id" class="form-control">
                                @foreach ($sockets as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('socket_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mobo_id">Motherboard</label>
                            <select name="mobo_id" id="mobo_id" class="form-control">
                                @foreach ($mobos as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('mobo_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="garansi_bulan">Garansi (bulan)</label>
                            <input type="number" name="garansi_bulan" id="garansi_bulan" class="form-control"
                                value="{{ old('garansi_bulan') }}">
                        </div>
                        <div class="form-group">
                            <label for="is_aktif">Aktif</label>
                            <select name="is_aktif" id="is_aktif" class="form-control">
                                <option value="1" {{ old('is_aktif') == '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('is_aktif') == '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <input type="file" name="gambar[]" id="gambar" class="form-control" multiple>
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
            $('#brand_id').on('change', function() {
                let isProc = $(this).data('ispro');
            });
        });
    </script>
@endpush
