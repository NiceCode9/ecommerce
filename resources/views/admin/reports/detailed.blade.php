@extends('admin.layouts.app', ['title' => 'Laporan Detail Penjualan'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Detail Penjualan</h3>
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
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->nomor_pesanan }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $order->pengguna->name ?? 'Guest' }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        @foreach ($order->detailPesanan as $item)
                                            <li>{{ $item->produk->nama }} ({{ $item->jumlah }} x Rp
                                                {{ number_format($item->harga, 0, ',', '.') }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-default">Kembali</a>
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="d-inline float-right">
                @csrf
                <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                <input type="hidden" name="report_type" value="detailed">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </form>
        </div>
    </div>
@endsection
