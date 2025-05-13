@extends('admin.layouts.app', ['title' => 'Laporan Penjualan Produk'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Penjualan Produk</h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $start_date }} - {{ $end_date }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        Total Pendapatan: <strong>Rp {{ number_format($total_revenue, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Terjual</th>
                            <th class="text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->nama }}</td>
                                <td>{{ $product->kategori->nama ?? '-' }}</td>
                                <td>{{ $product->total_terjual }}</td>
                                <td class="text-right">Rp {{ number_format($product->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-default">Kembali</a>
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                <input type="hidden" name="report_type" value="product">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-danger float-right">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </form>
        </div>
    </div>
@endsection
