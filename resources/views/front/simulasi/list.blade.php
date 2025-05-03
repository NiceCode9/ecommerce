@extends('front.layouts.main')

@section('title', 'Daftar Rakitan Saya')

@push('style')
    <style>
        .build-card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            padding: 15px;
            /* Added padding to prevent content from being too close to the edges */
        }

        .build-card:hover {
            transform: translateY(-5px);
        }

        .build-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .price-tag {
            font-weight: bold;
            color: #D10024;
        }
    </style>
@endpush

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Daftar Rakitan Saya</h3>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        @forelse ($builds as $build)
                            <div class="col-md-4 mb-4">
                                <div class="card build-card h-100">
                                    <div class="card-body">
                                        <span
                                            class="badge build-badge {{ $build->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($build->status) }}
                                        </span>
                                        <h5 class="card-title">{{ $build->kode }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            Dibuat: {{ $build->created_at->format('d M Y') }}
                                        </h6>
                                        <p class="card-text">
                                            <strong>Mode:</strong>
                                            {{ $build->mode === 'compatibility' ? 'Kompatibilitas' : 'Bebas' }}
                                        </p>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <span class="price-tag">Rp.
                                                {{ number_format($build->total_price, 0, ',', '.') }}</span>
                                            <div class="btn-group">
                                                <a href="{{ route('pelanggan.simulasi.show', $build) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <form action="{{ route('pelanggan.simulasi.destroy', $build) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Hapus rakitan ini?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    Anda belum memiliki rakitan. <a href="{{ route('simulasi.index') }}">Buat rakitan
                                        baru</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $builds->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }
        $(document).ready(function() {
            $('.price-tag').each(function() {
                var price = $(this).text().replace(/[^0-9]/g, '');
                $(this).text(formatCurrency(price));
            });
        });
    </script>
@endpush
