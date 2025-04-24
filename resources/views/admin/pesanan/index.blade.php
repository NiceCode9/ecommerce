@extends('admin.layouts.app', ['title' => 'Daftar Pesanan'])

@section('title', 'Daftar Pesanan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pesanan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanans as $pesanan)
                        <tr>
                            <td>{{ $pesanan->nomor_pesanan }}</td>
                            <td>{{ $pesanan->pengguna->name }}</td>
                            <td>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $pesanan->status == 'selesai' ? 'success' : ($pesanan->status == 'dibatalkan' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                                </span>
                            </td>
                            <td>{{ $pesanan->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $pesanans->links() }}
        </div>
    </div>
@endsection
