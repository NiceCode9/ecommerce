<div class="col-md-8">
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">Statistik Penjualan ({{ $startDate->format('d M Y') }} -
                {{ $endDate->format('d M Y') }})</h3>
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
    <script src="{{ asset('back') }}/plugins/chart.js/Chart.min.js"></script>
    <script>
        $(function() {
            // Sales Chart
            var salesCtx = $('#salesChart');
            var salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($salesChart['labels']) !!},
                    datasets: [{
                        label: 'Total Penjualan',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(60,141,188,1)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: 'rgba(60,141,188,1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: {!! json_encode($salesChart['data']) !!},
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                        ".");
                                }
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return 'Rp ' + tooltipItem.yLabel.toString().replace(
                                    /\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            });

            // Category Chart (tetap sama)
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
