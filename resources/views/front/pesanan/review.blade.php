@extends('front.layouts.main')

@section('title', 'Beri Ulasan Pesanan #' . $pesanan->nomor_pesanan)

@push('style')
    <style>
        .product-review-card {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
            height: 100%;
        }

        .rating-stars {
            display: inline-block;
            unicode-bidi: bidi-override;
            direction: rtl;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            font-size: 24px;
            color: #ddd;
            padding: 0 3px;
            cursor: pointer;
        }

        .rating-stars input:checked~label,
        .rating-stars label:hover,
        .rating-stars label:hover~label {
            color: #ffc107;
        }
    </style>
@endpush

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Beri Ulasan untuk Pesanan #{{ $pesanan->nomor_pesanan }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Silahkan beri ulasan untuk produk-produk berikut:
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="row">
                                @foreach ($produks as $item)
                                    @if (!$item->produk->hasBeenReviewed(auth()->id(), $pesanan->id))
                                        <div class="col-md-6 mb-4">
                                            <div class="product-review-card">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <img src="{{ asset('storage/' . optional($item->produk->gambarUtama)->gambar) }}"
                                                            alt="{{ $item->produk->nama }}" class="img-fluid">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h5>{{ $item->produk->nama }}</h5>
                                                        <form action="{{ route('produk.ulasan.store', $item->produk->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="pesanan_id"
                                                                value="{{ $pesanan->id }}">

                                                            <div class="form-group">
                                                                <label>Rating</label>
                                                                <div class="rating-stars">
                                                                    @for ($i = 5; $i >= 1; $i--)
                                                                        <input
                                                                            id="star-{{ $item->produk->id }}-{{ $i }}"
                                                                            name="rating" type="radio"
                                                                            value="{{ $i }}" required>
                                                                        <label
                                                                            for="star-{{ $item->produk->id }}-{{ $i }}">
                                                                            <i class="fa fa-star"></i>
                                                                        </label>
                                                                    @endfor
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Ulasan</label>
                                                                <textarea name="komentar" class="form-control" rows="3" required></textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Upload Gambar (Opsional)</label>
                                                                <input type="file" name="gambar"
                                                                    class="form-control-file" accept="image/*">
                                                            </div>

                                                            <button type="submit" class="btn btn-primary">
                                                                Submit Ulasan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5>{{ $item->produk->nama }}</h5>
                                                    <div class="alert alert-info">
                                                        Anda sudah memberikan ulasan untuk produk ini.
                                                    </div>
                                                    <a href="{{ route('produk.detail', $item->produk->slug) }}"
                                                        class="btn btn-sm btn-info">
                                                        Lihat Produk
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
