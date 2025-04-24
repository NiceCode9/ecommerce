@extends('admin.layouts.app', ['title', 'Detail Pesanan #' . $pesanan->nomor_pesanan])

@push('css')
    <style>
        /* resources/css/admin.css */
        .timeline {
            position: relative;
            padding-left: 50px;
            list-style: none;
        }

        .timeline:before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ddd;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -38px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #6c757d;
            border: 3px solid #ddd;
        }

        .timeline-header {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .timeline-body {
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .timeline .time {
            color: #6c757d;
            font-size: 0.8rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pesanan</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nomor Pesanan</dt>
                        <dd class="col-sm-8">{{ $pesanan->nomor_pesanan }}</dd>

                        <dt class="col-sm-4">Tanggal</dt>
                        <dd class="col-sm-8">{{ $pesanan->created_at->format('d M Y H:i') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span
                                class="badge badge-{{ $pesanan->status == 'selesai' ? 'success' : ($pesanan->status == 'dibatalkan' ? 'danger' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Total Pembayaran</dt>
                        <dd class="col-sm-8">Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</dd>

                        <dt class="col-sm-4">No. Resi</dt>
                        <dd class="col-sm-8">{{ $pesanan->nomor_resi ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Alamat Pengiriman</h3>
                </div>
                <div class="card-body">
                    <address>
                        <strong>{{ $pesanan->alamatPengiriman->nama_penerima }}</strong><br>
                        {{ $pesanan->alamatPengiriman->alamat_lengkap }}<br>
                        {{ $pesanan->alamatPengiriman->kota }}, {{ $pesanan->alamatPengiriman->provinsi }}<br>
                        <i class="fas fa-phone"></i> {{ $pesanan->alamatPengiriman->nomor_telepon }}
                    </address>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Items</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->detailPesanan as $item)
                                <tr>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Update Status</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pesanan.update-status', $pesanan->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>Dikirim
                                </option>
                                <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ $pesanan->status == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>No. Resi (jika dikirim)</label>
                            <input type="text" name="no_resi" class="form-control" value="{{ $pesanan->nomor_resi }}">
                        </div>
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Riwayat Status</h3>
        </div>
        <div class="card-body p-0">
            <ul class="timeline">
                @foreach ($pesanan->riwayatStatus as $riwayat)
                    <li>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i>
                                {{ $riwayat->created_at }}</span>
                            <h3 class="timeline-header">{{ ucfirst(str_replace('_', ' ', $riwayat->status)) }}</h3>
                            <div class="timeline-body">
                                {{ $riwayat->catatan }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
