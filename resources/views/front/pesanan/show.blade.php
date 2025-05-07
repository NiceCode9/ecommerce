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
                                            class="badge badge-{{ $pesanan->pembayaran->status == 'sukses' ? 'success' : 'warning' }}">
                                            {{ $pesanan->status == 'dibatalkan' ? '-' : $pesanan->pembayaran->status }}
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

                <!-- Kolom kanan -->
                <div class="col-md-4">
                    <!-- Card Alamat Pengiriman tetap sama -->
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

                    <!-- Blok Action Buttons -->
                    @if ($pesanan->pembayaran->metode == 'midtrans' && $pesanan->status == 'menunggu_pembayaran')
                        <div class="card mt-4">
                            <div class="card-body text-center">
                                <a href="{{ $pesanan->pembayaran->url_checkout }}" class="btn btn-primary btn-block mb-3">
                                    <i class="fa fa-credit-card"></i> Lanjutkan Pembayaran
                                </a>
                                <small class="text-muted">
                                    Link pembayaran berlaku hingga
                                    {{ \Carbon\Carbon::parse($pesanan->pembayaran->expired_at)->translatedFormat('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                    @endif

                    @if ($pesanan->status == 'dikirim')
                        <div class="card mt-4">
                            <div class="card-body text-center">
                                <div class="card mt-4">
                                    <div class="card-body text-center">
                                        <form action="{{ route('pelanggan.pesanan.action-confirm', $pesanan->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-block" name="actionConfirm"
                                                value="selesai">
                                                <i class="fa fa-check"></i> Pesanan Diterima
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($pesanan->status === 'selesai')
                        @php
                            $unreviewedProducts = $pesanan->detailPesanan->filter(function ($item) use ($pesanan) {
                                return !$item->produk->hasBeenReviewed(auth()->id(), $pesanan->id);
                            });
                        @endphp

                        @if ($unreviewedProducts->count() > 0)
                            @if ($unreviewedProducts->count() == 1)
                                <a href="{{ route('produk.detail', $unreviewedProducts->first()->produk->slug) }}?review=true&pesanan_id={{ $pesanan->id }}"
                                    class="btn btn-info btn-block">
                                    <i class="fa fa-comments"></i> Beri Ulasan
                                </a>
                            @else
                                <a href="{{ route('pelanggan.pesanan.review', $pesanan->id) }}"
                                    class="btn btn-info btn-block">
                                    <i class="fa fa-comments"></i> Beri Ulasan ({{ $unreviewedProducts->count() }} produk)
                                </a>
                            @endif
                        @else
                            <div class="alert alert-success">
                                Anda sudah memberikan ulasan untuk semua produk dalam pesanan ini.
                            </div>
                        @endif
                    @endif

                    @if (in_array($pesanan->status, ['menunggu_pembayaran', 'diproses']) && $pesanan->pembayaran->status != 'sukses')
                        <div class="card mt-4">
                            <div class="card-body">
                                <button class="btn btn-danger btn-block" data-toggle="modal"
                                    data-target="#cancelOrderModal">
                                    <i class="fa fa-times"></i> Batalkan Pesanan
                                </button>
                            </div>
                        </div>

                        <!-- Modal Alasan Pembatalan -->
                        <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Pembatalan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('pelanggan.pesanan.action-confirm', $pesanan->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Pilih alasan pembatalan:</label>
                                                <select name="alasan_pembatalan" class="form-control" required>
                                                    <option value="">-- Pilih Alasan --</option>
                                                    <option value="ubah_pesanan">Ingin mengubah pesanan</option>
                                                    <option value="metode_pembayaran">Ingin mengubah metode pembayaran
                                                    </option>
                                                    <option value="harga">Harga terlalu mahal</option>
                                                    <option value="waktu_pengiriman">Waktu pengiriman terlalu lama</option>
                                                    <option value="lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Catatan (opsional):</label>
                                                <textarea name="catatan_pembatalan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu"></textarea>
                                            </div>
                                            <input type="hidden" name="actionConfirm" value="dibatalkan">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
