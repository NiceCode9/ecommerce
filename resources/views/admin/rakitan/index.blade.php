@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.rakit.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Rakitan
                    </a>
                    <div class="table-responsive">
                        <table class="table table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Rakitan</th>
                                    <th>Kategori Rakitan</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->kategori->nama }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>Rp. {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('admin.rakit.edit', $item) }}" class="btn btn-warning btn-sm"
                                                title="Edit Rakitan">Edit</a>
                                            <form action="{{ route('admin.rakit.destroy', $item) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this item?')"
                                                    title="Delete Rakitan">Delete</button>
                                            </form>
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
