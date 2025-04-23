@extends('front.layouts.main')

@section('title', 'Pesanan #' . $pesanan->nomor_pesanan)

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Pesanan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Status Pesanan</h5>
                                    <div
                                        class="alert alert-{{ $pesanan->status == 'selesai'
                                            ? 'success'
                                            : (in_array($pesanan->status, ['dibatalkan', 'gagal'])
                                                ? 'danger'
                                                : 'warning') }}">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}</strong>
                                        @if ($pesanan->nomor_resi)
                                            <br>No. Resi: {{ $pesanan->nomor_resi }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Pembayaran</h5>
                                    <p>
                                        Metode: <strong>{{ strtoupper($pesanan->pembayaran->metode) }}</strong><br>
                                        Status: <span
                                            class="badge badge-{{ $pesanan->pembayaran->status == 'sukses'
                                                ? 'success'
                                                : ($pesanan->pembayaran->status == 'gagal'
                                                    ? 'danger'
                                                    : 'warning') }}">
                                            {{ $pesanan->pembayaran->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <h5>Items Pesanan</h5>
                            <table class="table table-bordered">
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
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Subtotal</th>
                                        <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Ongkos Kirim</th>
                                        <td>Rp {{ number_format($pesanan->biaya_pengiriman, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <td>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>Riwayat Status</h4>
                        </div>
                        <div class="card-body">
                            <ul class="timeline">
                                @foreach ($pesanan->riwayatStatus as $riwayat)
                                    <li class="mb-3">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $riwayat->status)) }}</strong>
                                        <small class="float-right text-muted">
                                            {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d F Y H:i') }}
                                        </small>
                                        <p>{{ $riwayat->catatan }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Alamat Pengiriman</h4>
                        </div>
                        <div class="card-body">
                            <address>
                                <strong>{{ $pesanan->alamatPengiriman->nama_penerima }}</strong><br>
                                {{ $pesanan->alamatPengiriman->alamat_lengkap }}<br>
                                {{ $pesanan->alamatPengiriman->kecamatan }}, {{ $pesanan->alamatPengiriman->kota }}<br>
                                {{ $pesanan->alamatPengiriman->provinsi }} {{ $pesanan->alamatPengiriman->kode_pos }}<br>
                                <i class="fa fa-phone"></i> {{ $pesanan->alamatPengiriman->nomor_telepon }}
                            </address>
                        </div>
                    </div>

                    @if ($pesanan->pembayaran->metode == 'midtrans' && $pesanan->status == 'menunggu_pembayaran')
                        <div class="card mt-4">
                            <div class="card-body text-center">
                                <a href="{{ $pesanan->pembayaran->url_checkout }}" class="btn btn-primary btn-block">
                                    <i class="fa fa-credit-card"></i> Lanjutkan Pembayaran
                                </a>
                                <small class="text-muted">
                                    Link pembayaran berlaku hingga
                                    {{ \Carbon\Carbon::parse($pesanan->pembayaran->expired_at)->translatedFormat('d M Y H:i') }}
                                    {{-- {{ $pesanan->pembayaran->expired_at->format('d M Y H:i') }} --}}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
