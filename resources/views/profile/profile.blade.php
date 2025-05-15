@extends('front.layouts.main')

@section('title', 'Profil Saya')

@section('content')
    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Navigation -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="avatar-container mb-3">
                                @if (auth()->user()->foto_profil)
                                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}"
                                        class="rounded-circle avatar-img" alt="Profile">
                                @else
                                    <div class="avatar-placeholder rounded-circle">
                                        <i class="fa fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="mb-1 font-weight-bold">{{ auth()->user()->name }}</h5>
                            <p class="text-muted small">{{ auth()->user()->email }}</p>

                            <hr class="my-3">

                            <ul class="nav flex-column profile-nav">
                                <li class="nav-item active">
                                    <a class="nav-link" href="#profile-info">
                                        <i class="fa fa-user mr-2"></i> Profil Saya
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#change-password">
                                        <i class="fa fa-lock mr-2"></i> Ubah Password
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pelanggan.pesanan.index') }}">
                                        <i class="fa fa-shopping-bag mr-2"></i> Pesanan Saya
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pelanggan.wishlist.index') }}">
                                        <i class="fa fa-heart mr-2"></i> Wishlist
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Profile Information -->
                    <div class="card shadow-sm mb-4" id="profile-info">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h4 class="mb-0 font-weight-bold">Informasi Profil</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert"
                                                    style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email"
                                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert"
                                                    style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Telepon</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('nomor_telepon') is-invalid @enderror"
                                                name="nomor_telepon"
                                                value="{{ old('nomor_telepon', auth()->user()->nomor_telepon) }}">
                                            @error('nomor_telepon')
                                                <span class="invalid-feedback" role="alert"
                                                    style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date"
                                                class="form-control form-control-lg @error('tanggal_lahir') is-invalid @enderror"
                                                name="tanggal_lahir"
                                                value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir ? \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->format('Y-m-d') : null) }}">
                                            @error('tanggal_lahir')
                                                <span class="invalid-feedback" role="alert"
                                                    style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="btn-group btn-group-toggle w-100 @error('jenis_kelamin') is-invalid @enderror"
                                        data-toggle="buttons">
                                        <label
                                            class="btn btn-outline-secondary {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Laki-laki' ? 'active' : '' }}">
                                            <input type="radio" name="jenis_kelamin" value="Laki-laki" autocomplete="off"
                                                {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }}>
                                            Laki-laki
                                        </label>
                                        <label
                                            class="btn btn-outline-secondary {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Perempuan' ? 'active' : '' }}">
                                            <input type="radio" name="jenis_kelamin" value="Perempuan"
                                                autocomplete="off"
                                                {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}>
                                            Perempuan
                                        </label>
                                        <label
                                            class="btn btn-outline-secondary {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Lainnya' ? 'active' : '' }}">
                                            <input type="radio" name="jenis_kelamin" value="Lainnya"
                                                autocomplete="off"
                                                {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Lainnya' ? 'checked' : '' }}>
                                            Lainnya
                                        </label>
                                    </div>
                                    @error('jenis_kelamin')
                                        <span class="invalid-feedback d-block" role="alert"
                                            style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="card shadow-sm mb-4" id="change-password">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h4 class="mb-0 font-weight-bold">Ubah Password</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password"
                                        class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                        name="current_password" required>
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert"
                                            style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"
                                            style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password"
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" required>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert"
                                            style="color:#dc3545; font-size: 0.9em; font-style: italic;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">Ubah Password</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Address Management -->
                    <div class="card shadow-sm">
                        <div
                            class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 font-weight-bold">Daftar Alamat</h4>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#addAddressModal">
                                <i class="fa fa-plus mr-1"></i> Tambah Alamat
                            </button>
                        </div>
                        <div class="card-body">
                            @if ($alamats->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fa fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Anda belum memiliki alamat. Tambahkan alamat sekarang.</p>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addAddressModal">
                                        <i class="fa fa-plus mr-1"></i> Tambah Alamat
                                    </button>
                                </div>
                            @else
                                <div class="row" id="alamat-list-container">

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editAddressForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id">
                    <input type="hidden" name="api_id">
                    <input type="hidden" name="label">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Alamat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields sama dengan addAddressModal -->
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <input type="text" class="form-control" name="nama_penerima" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" class="form-control" name="nomor_telepon" required>
                        </div>
                        <div class="form-group">
                            <label>Cari Alamat</label>
                            <input type="text" class="form-control" name="cari-alamat" readonly>
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat_lengkap" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <input type="text" class="form-control" name="provinsi" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kota</label>
                                    <input type="text" class="form-control" name="kota" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <input type="text" class="form-control" name="kecamatan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelurahan</label>
                                    <input type="text" class="form-control" name="kelurahan" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" name="kode_pos" required>
                        </div>
                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea class="form-control" name="catatan" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="is_utama" id="editIsUtama">
                                <label class="custom-control-label" for="editIsUtama">Jadikan alamat utama</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom Styles */
        .avatar-container {
            width: 100px;
            height: 100px;
            margin: 0 auto;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 2.5rem;
        }

        .profile-nav .nav-item {
            margin-bottom: 5px;
        }

        .profile-nav .nav-link {
            color: #495057;
            border-radius: 4px;
            padding: 10px 15px;
        }

        .profile-nav .nav-link:hover {
            background-color: #f8f9fa;
            color: #D10024;
        }

        .profile-nav .nav-link.active {
            background-color: #D10024;
            color: white;
        }

        .profile-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-group-toggle .btn {
            border-radius: 4px !important;
            margin: 0 2px;
        }

        .btn-group-toggle .btn.active {
            background-color: #D10024;
            color: white;
            border-color: #D10024;
        }
    </style>
@endsection


@push('front-script')
    <script>
        $(document).ready(function() {
            loadAlamatList();
        });
    </script>
@endpush
