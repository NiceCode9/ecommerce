@extends('front.layouts.main')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
    <!-- BREADCRUMB -->
    {{-- <div id="breadcrumb" class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">Checkout</h3>
                    <ul class="breadcrumb-tree">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li class="active">Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <div class="container">
            <form id="checkoutForm" action="" method="POST">
                @csrf
                <input type="hidden" name="cart_ids" value="{{ json_encode($cartIds) }}">

                <div class="row">
                    <div class="col-md-7">
                        <!-- Shipping Address -->
                        <div class="billing-details">
                            <div class="section-title">
                                <h3 class="title">Alamat Pengiriman</h3>
                            </div>

                            <div class="form-group">
                                <label>Pilih Alamat</label>
                                <select class="input" name="alamat_id" id="alamatSelect" required>
                                    <option value="">-- Pilih Alamat --</option>
                                    @foreach ($alamats as $alamat)
                                        <option value="{{ $alamat->id }}" {{ $alamat->is_utama ? 'selected' : '' }}
                                            data-city-id="{{ $alamat->city_id }}">
                                            {{ $alamat->nama_penerima }} - {{ $alamat->alamat_lengkap }}
                                            ({{ $alamat->is_utama ? 'Utama' : '' }})
                                        </option>
                                    @endforeach
                                </select>
                                <a href="" class="btn btn-link">+ Tambah Alamat
                                    Baru</a>
                            </div>

                            <!-- Display selected address details -->
                            <div id="alamatDetail" class="well" style="margin-top: 15px; display: none;">
                                <h4><strong id="namaPenerima"></strong></h4>
                                <p id="alamatLengkap"></p>
                                <p id="detailAlamat"></p>
                                <p id="nomorTelepon"></p>
                            </div>

                            <!-- Shipping Method -->
                            <div class="section-title" style="margin-top: 30px;">
                                <h3 class="title">Metode Pengiriman</h3>
                            </div>

                            <div class="form-group">
                                <label>Pilih Kurir</label>
                                <select class="input" name="courier" id="courierSelect" required disabled>
                                    <option value="">-- Pilih setelah memilih alamat --</option>
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS Indonesia</option>
                                </select>
                            </div>

                            <div class="form-group" id="serviceOptions" style="display: none;">
                                <label>Pilih Layanan</label>
                                <div id="serviceList">
                                    <!-- Options will be populated via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <!-- Order Summary -->
                        <div class="order-details">
                            <div class="section-title text-center">
                                <h3 class="title">Ringkasan Pesanan</h3>
                            </div>

                            <div class="order-summary">
                                <div class="order-col">
                                    <div><strong>PRODUK</strong></div>
                                    <div><strong>TOTAL</strong></div>
                                </div>

                                <div class="order-products">
                                    @foreach ($cartItems as $item)
                                        <div class="order-col">
                                            <div>{{ $item->jumlah }}x {{ $item->produk->nama }}</div>
                                            <div>Rp
                                                {{ number_format($item->produk->harga_setelah_diskon * $item->jumlah, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="order-col">
                                    <div>Subtotal</div>
                                    <div>Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                                </div>

                                <div class="order-col">
                                    <div>Ongkos Kirim</div>
                                    <div id="shippingCost">Rp 0</div>
                                </div>

                                <div class="order-col">
                                    <div><strong>TOTAL</strong></div>
                                    <div><strong class="order-total" id="grandTotal">Rp
                                            {{ number_format($subtotal, 0, ',', '.') }}</strong></div>
                                </div>
                            </div>

                            <div class="payment-method">
                                <div class="input-radio">
                                    <input type="radio" name="payment_method" id="payment-1" value="midtrans" checked>
                                    <label for="payment-1">
                                        <span></span>
                                        Pembayaran Online (Midtrans)
                                    </label>
                                </div>
                            </div>

                            <div class="input-checkbox">
                                <input type="checkbox" id="terms" required>
                                <label for="terms">
                                    <span></span>
                                    Saya menyetujui <a href="#">syarat dan ketentuan</a>
                                </label>
                            </div>

                            <button type="submit" class="primary-btn order-submit" id="submitBtn" disabled>
                                Lanjut ke Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /SECTION -->
@endsection

@push('front-script')
    <script>
        $(document).ready(function() {
            // Tampilkan detail alamat saat dipilih
            $('#alamatSelect').change(function() {
                const selectedOption = $(this).find('option:selected');
                if (selectedOption.val()) {
                    $('#alamatDetail').show();
                    $('#namaPenerima').text(selectedOption.text().split(' - ')[0]);
                    $('#alamatLengkap').text(selectedOption.text().split(' - ')[1]);

                    // Enable courier selection
                    $('#courierSelect').prop('disabled', false);

                    // Hitung ongkir saat courier dipilih
                    $('#courierSelect').change(function() {
                        if ($(this).val()) {
                            hitungOngkir();
                        }
                    });
                } else {
                    $('#alamatDetail').hide();
                    $('#courierSelect').prop('disabled', true);
                    $('#serviceOptions').hide();
                    $('#submitBtn').prop('disabled', true);
                }
            });

            // Fungsi hitung ongkir via RajaOngkir API
            function hitungOngkir() {
                const cityId = $('#alamatSelect option:selected').data('city-id');
                const courier = $('#courierSelect').val();
                const weight = {{ $totalWeight }}; // Total berat dari controller

                showLoading();

                $.ajax({
                    url: "{{ route('pelanggan.checkout.calculate-shipping') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        city_id: cityId,
                        courier: courier,
                        weight: weight
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#serviceList').empty();

                            response.services.forEach(service => {
                                const serviceId = `${courier}_${service.service}`;
                                const serviceName =
                                    `${service.service} - ${service.description}`;
                                const cost = service.cost[0].value;
                                const etd = service.cost[0].etd;

                                $('#serviceList').append(`
                            <div class="input-radio">
                                <input type="radio" name="shipping_service" id="${serviceId}"
                                    value="${service.service}" data-cost="${cost}">
                                <label for="${serviceId}">
                                    <span></span>
                                    ${serviceName} (${etd} hari) - Rp ${cost.toLocaleString('id-ID')}
                                </label>
                            </div>
                        `);
                            });

                            $('#serviceOptions').show();

                            // Update total saat layanan dipilih
                            $('input[name="shipping_service"]').change(function() {
                                const cost = $(this).data('cost');
                                const subtotal = {{ $subtotal }};
                                const grandTotal = subtotal + cost;

                                $('#shippingCost').text('Rp ' + cost.toLocaleString('id-ID'));
                                $('#grandTotal').text('Rp ' + grandTotal.toLocaleString(
                                    'id-ID'));

                                // Enable submit button
                                $('#submitBtn').prop('disabled', false);
                            });
                        } else {
                            alert('Gagal menghitung ongkos kirim: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menghitung ongkos kirim');
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            }

            function showLoading() {
                $('#ajax-loader').show();
            }

            function hideLoading() {
                $('#ajax-loader').hide();
            }
        });
    </script>
@endpush
