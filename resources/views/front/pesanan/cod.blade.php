@extends('front.layouts.main')

@section('title', 'Konfirmasi Pembayaran COD')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                @if ($errors->any())
                    <div class="col-md-6">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Konfirmasi Pembayaran COD</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Total yang harus dibayar:</strong>
                                Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}
                            </div>

                            <form action="{{ route('pelanggan.pesanan.confirm-cod', $pesanan->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label>Upload Bukti Pembayaran</label>
                                    <input type="file" name="bukti_pembayaran" class="form-control-file" required>
                                    <small class="form-text text-muted">
                                        Upload bukti transfer atau foto uang tunai (max 2MB)
                                    </small>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-check-circle"></i> Konfirmasi Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>

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
                                                <select name="cancel_reason" class="form-control" required>
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
                                                <textarea name="cancel_note" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu"></textarea>
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

                    <div class="card mt-4">
                        <div class="card-body">
                            <h5>Instruksi Pembayaran:</h5>
                            <ol>
                                <li>Siapkan uang tunai sejumlah total pesanan</li>
                                <li>Bayarkan kepada kurir saat barang diterima</li>
                                <li>Ambil foto uang atau bukti transfer</li>
                                <li>Upload sebagai bukti pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
