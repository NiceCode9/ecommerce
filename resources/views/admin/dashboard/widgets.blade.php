<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3>{{ $totalOrders }}</h3>
            <p>Total Pesanan</p>
        </div>
        <div class="icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <a href="{{ route('admin.pesanan.index') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <h3>{{ $totalRevenue }}<sup style="font-size: 20px">IDR</sup></h3>
            <p>Pendapatan</p>
        </div>
        <div class="icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <a href="" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

<div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
        <div class="inner">
            <h3>{{ $totalProducts }}</h3>
            <p>Produk</p>
        </div>
        <div class="icon">
            <i class="fas fa-box-open"></i>
        </div>
        <a href="{{ route('admin.produk.index') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

<div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
        <div class="inner">
            <h3>{{ $pendingPayments }}</h3>
            <p>Pembayaran Pending</p>
        </div>
        <div class="icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <a href="" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
