<div class="row">
    <!-- Produk Terlaris -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">10 Produk Terlaris</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Terjual</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bestSellingProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td>{{ $product->total_terjual ?? 0 }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $product->stok > 10 ? 'success' : ($product->stok > 0 ? 'warning' : 'danger') }}">
                                        {{ $product->stok }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Produk Paling Sering Dilihat -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">10 Produk Paling Sering Dilihat</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Dilihat</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mostViewedProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td>{{ $product->dilihat }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $product->rating >= 4 ? 'success' : ($product->rating >= 2 ? 'warning' : 'danger') }}">
                                        {{ number_format($product->rating, 1) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Produk dengan Rating Tertinggi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">10 Produk dengan Rating Tertinggi</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Rating</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topRatedProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ number_format($product->rating, 1) }}
                                    </span>
                                </td>
                                <td>{{ $product->ulasan->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Produk dengan Rating Terendah -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">10 Produk dengan Rating Terendah</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Rating</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowRatedProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td>
                                    <span class="badge badge-danger">
                                        {{ number_format($product->rating, 1) }}
                                    </span>
                                </td>
                                <td>{{ $product->ulasan->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Produk yang Hampir Habis Stok -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-0 bg-warning">
                <h3 class="card-title">10 Produk yang Hampir Habis Stok</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td>
                                    <span class="badge badge-warning">
                                        {{ $product->stok }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Produk yang Habis Stok -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-0 bg-danger">
                <h3 class="card-title text-white">10 Produk yang Habis Stok</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outOfStockProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td>
                                    <span class="badge badge-danger">
                                        {{ $product->stok }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
