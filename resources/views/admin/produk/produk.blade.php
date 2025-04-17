@extends('admin.layouts.app', ['title' => 'Data Produk'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary btn-sm mb-3 float-right">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Brand</th>
                                    <th>Garansi</th>
                                    <th>Harga</th>
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
                                        <td>{{ $product->stok }}</td>
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
                                            <a href="" class="btn btn-success btn-sm" title="Gambar Produk">
                                                <i class="fas fa-images"></i> Gambar
                                            </a>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-warning" title="Edit Produk">
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
@endsection
