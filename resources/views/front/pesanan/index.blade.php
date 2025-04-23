@extends('front.layouts.main')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-center">Pesanan Saya</h2>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanans as $pesanan)
                                <tr>
                                    <td>{{ $pesanan->nomor_pesanan }}</td>
                                    <td>{{ $pesanan->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge
                                    @if ($pesanan->status == 'selesai') badge-success
                                    @elseif(in_array($pesanan->status, ['dibatalkan', 'gagal'])) badge-danger
                                    @else badge-warning @endif">
                                            {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pelanggan.pesanan.show', $pesanan->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $pesanans->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
