@extends('admin.layouts.app', ['title' => 'Laporan Penjualan per Kategori'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Penjualan per Kategori</h3>
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
                            <th>Kategori</th>
                            <th>Produk Terjual</th>
                            <th class="text-right">Pendapatan</th>
                            <th class="text-right">% dari Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->nama }}</td>
                                <td>{{ $category->total_terjual }}</td>
                                <td class="text-right">Rp {{ number_format($category->total_pendapatan, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ $total_revenue > 0 ? number_format(($category->total_pendapatan / $total_revenue) * 100, 2) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-default">Kembali</a>
            <a href="{{ route('admin.reports.generate', [
                'start_date' => request()->start_date,
                'end_date' => request()->end_date,
                'report_type' => 'category',
                'export' => 'pdf',
            ]) }}"
                class="btn btn-danger float-right">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
@endsection
