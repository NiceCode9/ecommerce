@extends('admin.layouts.app', ['title' => 'Laporan Penjualan'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Generate Laporan Penjualan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.generate') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="report_type">Jenis Laporan</label>
                            <select class="form-control" id="report_type" name="report_type" required>
                                <option value="summary">Ringkasan Penjualan</option>
                                <option value="detailed">Detail Transaksi</option>
                                <option value="product">Penjualan Produk</option>
                                <option value="category">Penjualan per Kategori</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generate Laporan</button>
                <button type="submit" name="export" value="pdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </form>
        </div>
    </div>
@endsection
