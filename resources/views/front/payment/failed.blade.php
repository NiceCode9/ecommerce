@extends('front.layouts.main')

@section('title', 'Pembayaran Gagal')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card">
                        <div class="card-body py-5">
                            <div class="mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24"
                                    fill="#dc3545" class="mb-3">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                                <h2 class="text-danger">Pembayaran Gagal</h2>
                                <p class="lead">Terjadi kesalahan dalam proses pembayaran</p>
                            </div>

                            <div class="alert alert-danger">
                                <h5>Nomor Pesanan: <strong>{{ $pesanan->nomor_pesanan }}</strong></h5>
                                <p>Total Pembayaran: <strong>Rp
                                        {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</strong></p>
                            </div>

                            <div class="mb-4">
                                <p>Silakan coba lagi atau hubungi tim support kami.</p>
                                <p>Pesanan Anda tetap tersimpan dan dapat dibayar dalam 24 jam.</p>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-home"></i> Kembali ke Beranda
                                </a>
                                <a href="{{ route('pelanggan.pesanan.show', $pesanan->id) }}" class="btn btn-primary">
                                    <i class="fa fa-credit-card"></i> Coba Lagi
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <div class="alert alert-warning">
                            <h5>Butuh Bantuan?</h5>
                            <p>Hubungi kami di <a href="tel:+628123456789">0812-3456-789</a> atau <a
                                    href="mailto:cs@example.com">cs@example.com</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
