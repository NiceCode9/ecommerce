@extends('front.layouts.main')

@section('title', 'Detail Rakitan - ' . $build->kode)

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Detail Rakitan</h3>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>{{ $build->kode }}</h4>
                            <span class="badge {{ $build->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($build->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Tanggal Dibuat:</strong> {{ $build->created_at->format('d F Y H:i') }}</p>
                                    <p><strong>Mode:</strong>
                                        {{ $build->mode === 'compatibility' ? 'Dengan Kompatibilitas' : 'Tanpa Kompatibilitas' }}
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h3 class="price-tag">Rp. {{ number_format($build->total_price, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('pelanggan.checkout.index') }}">
                        <div class="card">
                            <div class="card-header">
                                <h4>Komponen Rakitan</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Komponen</th>
                                                <th>Nama Produk</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-end">Harga Satuan</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($build->components as $component)
                                                <tr>
                                                    <td>
                                                        {{ ucfirst($component->component_type) }}
                                                        <input type="hidden" name="cart_ids[]"
                                                            value="{{ $component->id }}">
                                                    </td>
                                                    <td>{{ $component->produk->nama }}</td>
                                                    <td class="text-center">{{ $component->quantity }}</td>
                                                    <td class="text-end">
                                                        Rp.
                                                        {{ number_format($component->produk->harga_setelah_diskon, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-end">Rp.
                                                        {{ number_format($component->subtotal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-end">Rp.
                                                    {{ number_format($build->total_price, 0, ',', '.') }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            {{-- <a href="{{ route('pelanggan.simulasi.list') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali ke Daftar
                            </a> --}}
                            {{-- <div> --}}
                            <a href="{{ route('simulasi.index') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Buat Rakitan Baru
                            </a>
                            <input type="hidden" name="mode" value="simulasi">
                            <input type="hidden" name="build_id" value="{{ $build->id }}">
                            <button type="submit" class="btn btn-success">Checkout</button>
                            {{-- </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
