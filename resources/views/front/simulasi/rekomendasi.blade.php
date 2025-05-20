@extends('front.layouts.main')

@section('title', 'Rekomendasi Sumulasi')


@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Rekomendasi Rakitan</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Kategori PC</th>
                                            <th>Brand</th>
                                            <th>Total Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($builds as $build)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $build->kode }}
                                                </td>
                                                <td>{{ $build->kategori->nama }}</td>
                                                <td>{{ $build->brand->nama }}</td>
                                                <td class="text-end">Rp.
                                                    {{ number_format($build->total_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('pelanggan.simulasi.show', $build) }}"
                                                        class="btn btn-info btn-sm">Lihat Component</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        {{-- <div> --}}
                        <a href="{{ route('simulasi.index') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Custom Build
                        </a>
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $builds->links() }}
            </div>
        </div>
    </div>
@endsection
