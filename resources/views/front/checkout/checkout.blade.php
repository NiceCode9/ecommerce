@extends('front.layouts.main')

@push('style')
    <style>
        .shipping-options {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 4px;
        }

        .shipping-option {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .shipping-option:hover {
            border-color: #D10024;
        }

        .shipping-option label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            cursor: pointer;
        }

        .shipping-method {
            font-weight: bold;
            flex: 2;
        }

        .shipping-etd {
            color: #666;
            flex: 1;
            text-align: center;
        }

        .shipping-cost {
            color: #D10024;
            font-weight: bold;
            flex: 1;
            text-align: right;
        }

        input[type="radio"]:checked+label {
            color: #D10024;
        }
    </style>
@endpush

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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @endif
            <form id="checkoutForm" action="{{ route('pelanggan.checkout.process') }}" method="POST">
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
                                    {{-- @foreach ($alamats as $alamat)
                                        <option value="{{ $alamat->id }}" {{ $alamat->is_utama ? 'selected' : '' }}
                                            data-city-id="{{ $alamat->city_id }}">
                                            {{ $alamat->nama_penerima }} - {{ $alamat->alamat_lengkap }}
                                            ({{ $alamat->is_utama ? 'Utama' : '' }})
                                        </option>
                                    @endforeach --}}
                                </select>
                                <a href="#" data-toggle="modal" data-target="#addAddressModal" class="btn btn-link">
                                    <i class="fa fa-plus"></i> Tambah Alamat Baru
                                </a>
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

                            {{-- <div class="form-group">
                                <label>Pilih Kurir</label>
                                <select class="input" name="courier" id="courierSelect" required disabled>
                                    <option value="">-- Pilih setelah memilih alamat --</option>
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS Indonesia</option>
                                </select>
                            </div> --}}

                            <div class="form-group" id="serviceOptions">
                                <label>Pilih Layanan</label>
                                <div id="serviceList" class="shipping-options">
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
                                            <div>{{ $item->jumlah ?? $item->quantity }}x {{ $item->produk->nama }}</div>
                                            <div>Rp
                                                {{ number_format($item->produk->harga_setelah_diskon * ($item->jumlah ?? $item->quantity), 0, ',', '.') }}
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
                                    <input type="hidden" name="shipping_cost" value="0">
                                </div>

                                <div class="order-col">
                                    <div><strong>TOTAL</strong></div>
                                    <div><strong class="order-total" id="grandTotal">Rp
                                            {{ number_format($subtotal, 0, ',', '.') }}</strong></div>
                                    <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
                                </div>
                            </div>

                            <div class="payment-method">
                                <div class="input-radio">
                                    <input type="radio" name="payment_method" id="payment-1" value="cod" checked>
                                    <label for="payment-1">
                                        <span></span>
                                        COD
                                    </label>
                                </div>
                                <div class="input-radio">
                                    <input type="radio" name="payment_method" id="payment-2" value="midtrans" checked>
                                    <label for="payment-2">
                                        <span></span>
                                        Pembarayan Online
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
    {{-- @foreach ($alamats as $alamat)
            <option value="{{ $alamat->id }}" {{ $alamat->is_utama ? 'selected' : '' }}
            data-city-id="{{ $alamat->city_id }}">
            {{ $alamat->nama_penerima }} - {{ $alamat->alamat_lengkap }}
            ({{ $alamat->is_utama ? 'Utama' : '' }})
        </option>
    @endforeach --}}
@endsection

@push('front-script')
    <script>
        $(document).ready(function() {
            getAlamatCheckout();
            // Tampilkan detail alama
            // t saat dipilih
            $('#alamatSelect').change(function() {
                const selectedOption = $(this).find('option:selected');
                console.log(selectedOption.val());

                if (selectedOption.val()) {
                    $('#alamatDetail').show();
                    $('#namaPenerima').text(selectedOption.data('nama-penerima'));
                    $('#alamatLengkap').text(selectedOption.data('label'));
                    $('#detailAlamat').text(selectedOption.data('alamat-lengkap'));
                    $('#nomorTelepon').text(selectedOption.data('no-telp'));

                    const destination = parseInt(selectedOption.data('city-id'));
                    const totalItems = parseInt({{ $totalItems }});
                    const weight = {{ $totalWeight }} < 1 ? 1 : {{ $totalWeight }};

                    calculateShipping(destination, totalItems, weight);

                } else {
                    $('#alamatDetail').hide();
                    // $('#courierSelect').prop('disabled', true);
                    // $('#serviceOptions').hide();
                    $('#submitBtn').prop('disabled', true);
                }
            });

            function calculateShipping(destination, totalItem, weight) {
                // showLoading();

                $.ajax({
                    url: "{{ route('api.calculate-shipping') }}",
                    type: 'get',
                    data: {
                        _token: '{{ csrf_token() }}',
                        receiver_destination_id: destination,
                        weight: weight,
                        item_value: totalItem
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.success) {
                            renderShippingOptions(response.data);
                        } else {
                            showToast('Gagal menghitung ongkir: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        showToast('Terjadi kesalahan saat menghitung ongkos kirim');
                    },
                    complete: function() {
                        // hideLoading();
                    }
                });
            }

            function renderShippingOptions(data) {
                const $serviceList = $('#serviceList');
                $serviceList.empty();

                // Filter hanya layanan reguler
                const regularServices = data.data.calculate_reguler;

                if (regularServices.length > 0) {
                    regularServices.forEach(service => {
                        const serviceId = `${service.shipping_name.toLowerCase()}_${service.service_name}`;
                        const serviceName = `${service.shipping_name} - ${service.service_name}`;
                        const cost = service.shipping_cost;
                        const etd = service.etd || '1-3 hari';

                        $serviceList.append(`
                    <div class="shipping-option">
                        <input type="radio" name="shipping_service"
                               id="${serviceId}"
                               value="${service.service_name}"
                               data-cost="${cost}"
                               data-etd="${etd}"
                               data-courier="${service.shipping_name}">
                               <input type="hidden" name="shipping_name" value="${service.shipping_name}">
                        <label for="${serviceId}">
                            <span class="shipping-method">${serviceName}</span>
                            <span class="shipping-etd">${etd}</span>
                            <span class="shipping-cost">Rp ${cost.toLocaleString('id-ID')}</span>
                        </label>
                    </div>
                `);
                    });

                    $('#serviceOptions').show();

                    // Update total saat layanan dipilih
                    $('input[name="shipping_service"]').change(function() {
                        updateOrderSummary($(this).data('cost'));
                    });
                } else {
                    $serviceList.append('<p class="text-muted">Tidak ada layanan tersedia</p>');
                }
            }

            function updateOrderSummary(shippingCost) {
                const subtotal = {{ $subtotal }};
                const grandTotal = subtotal + parseInt(shippingCost);

                $('#shippingCost').text('Rp ' + shippingCost.toLocaleString('id-ID'));
                $('input[name="shipping_cost"]').val(shippingCost);
                $('#grandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                // $('#shippingCostInput').val(grandTotal);

                // Enable submit button
                $('#submitBtn').prop('disabled', false);
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
