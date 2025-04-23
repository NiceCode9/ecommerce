@extends('front.layouts.main')

@section('title', 'Konfirmasi Pembayaran COD')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
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
