@extends('front.layouts.main')

@section('title', 'Pembayaran Berhasil')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card">
                        <div class="card-body py-5">
                            <div class="mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24"
                                    fill="#28a745" class="mb-3">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <h2 class="text-success">Pembayaran Berhasil!</h2>
                                <p class="lead">Terima kasih telah berbelanja di {{ config('app.name') }}</p>
                            </div>

                            <div class="alert alert-success">
                                <h5>Nomor Pesanan: <strong>{{ $pesanan->nomor_pesanan }}</strong></h5>
                                <p>Total Pembayaran: <strong>Rp
                                        {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</strong></p>
                            </div>

                            <div class="mb-4">
                                <p>Kami telah mengirimkan detail pesanan ke email Anda.</p>
                                <p>Pesanan akan diproses dalam 1x24 jam.</p>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-home"></i> Kembali ke Beranda
                                </a>
                                <a href="{{ route('pelanggan.pesanan.show', $pesanan->id) }}" class="btn btn-primary">
                                    <i class="fa fa-list"></i> Lihat Detail Pesanan
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center text-muted">
                        <small>Jika ada pertanyaan, hubungi kami di <a
                                href="mailto:cs@example.com">cs@example.com</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
