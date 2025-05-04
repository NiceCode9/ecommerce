<div class="col-md-8">
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">Statistik Penjualan 30 Hari Terakhir</h3>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">Distribusi Kategori Produk</h3>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            // Sales Chart
            var salesChart = new Chart($('#salesChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($salesChart['labels']) !!},
                    datasets: [{
                        label: 'Penjualan',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        data: {!! json_encode($salesChart['data']) !!}
                    }]
                }
            });

            // Category Chart
            var categoryChart = new Chart($('#categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryChart['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($categoryChart['data']) !!},
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
