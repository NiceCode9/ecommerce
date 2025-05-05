<div class="col-12">
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">Pesanan Terbaru</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-tool btn-sm">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td>{{ $order->nomor_pesanan }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ format_currency($order->total_bayar) }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
