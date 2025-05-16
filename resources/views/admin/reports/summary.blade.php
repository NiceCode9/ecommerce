@extends('admin.layouts.app', ['title' => 'Laporan Ringkasan Penjualan'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Ringkasan Penjualan</h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $start_date }} - {{ $end_date }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pendapatan</span>
                            <span class="info-box-number">Rp {{ number_format($total_revenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Transaksi</span>
                            <span class="info-box-number">{{ number_format($total_orders, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rata-rata per Transaksi</span>
                            <span class="info-box-number">Rp {{ number_format($average_order_value, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Penjualan Harian</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="dailySalesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Pesanan</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-default">Kembali</a>
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="d-inline float-right">
                @csrf
                <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                <input type="hidden" name="report_type" value="summary">
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('back') }}/plugins/chart.js/Chart.min.js"></script>
    <script>
        $(function() {
            // Daily Sales Chart
            var dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
            var dailySalesChart = new Chart(dailySalesCtx, {
                type: 'bar',
                data: {
                    labels: @json($daily_sales['dates']),
                    datasets: [{
                        label: 'Penjualan',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        data: @json($daily_sales['sales'])
                    }]
                }
            });

            // Order Status Chart
            var orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            var orderStatusChart = new Chart(orderStatusCtx, {
                type: 'pie',
                data: {
                    labels: @json(array_keys($orders_by_status->toArray())),
                    datasets: [{
                        data: @json(array_values($orders_by_status->toArray())),
                        backgroundColor: [
                            '#f56954', '#00a65a', '#f39c12', '#00c0ef',
                            '#3c8dbc', '#d2d6de', '#FF6384', '#36A2EB'
                        ]
                    }]
                }
            });
        });
    </script>
@endpush
