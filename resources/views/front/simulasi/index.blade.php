@extends('front.layouts.main')

@push('style')
    <style>
        .form-section {
            margin-bottom: 20px;
        }

        .section-title {
            color: #333;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #E4E7ED;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .search-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .preview-btn {
            background-color: #00b050;
            border-color: #00b050;
            color: white;
        }

        .preview-btn:hover {
            background-color: #008f40;
            border-color: #008f40;
            color: white;
        }

        .component-price {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .price {
            font-weight: bold;
            text-align: right;
            color: #D10024;
        }

        .form-group label {
            font-weight: 500;
        }

        .form-control {
            height: 40px;
            margin-bottom: 10px;
        }

        .quantity-input {
            text-align: center;
        }


        select.input-select {
            width: 100%;
            height: 40px;
            padding: 0 10px;
            border: 1px solid #E4E7ED;
            border-radius: 4px;
            background-color: #FFF;
        }
    </style>
@endpush

@section('content')
    <div class="section">
        <div class="container">
            <!-- Search Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="search-header">
                        <h4 class="mb-4">Cari Kode Simulasi</h4>
                        <div class="header-search">
                            <form>
                                <input class="input" type="text" placeholder="Kode Simulasi">
                                <button class="search-btn preview-btn">Preview</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h3 class="section-title">PENGECEKAN KOMPATIBILITAS</h3>

                        <form action="" method="POST">
                            @csrf
                            <!-- Compatibility Selection -->
                            <div class="form-section row">
                                <div class="col-md-3">
                                    <label>Pilih Kompatibilitas</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <select class="input-select" id="mode-select">
                                            <option value="compatibility">Dengan Kompatibilitas</option>
                                            <option value="free">Tanpa Kompatibilitas</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Processor Brand -->
                            <div class="form-section row">
                                <div class="col-md-3">
                                    <label>Pilih Brand Processor</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <select class="input-select" id="brand-select">
                                            <option value="">Pilih Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Socket -->
                            <div class="form-section row">
                                <div class="col-md-3">
                                    <label>Pilih Socket</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <select class="input-select" id="socket-select">
                                            <option value="">Pilih Brand terlebih dahulu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Processor Selection -->
                            <div class="form-section row align-items-center">
                                <div class="col-md-3">
                                    <label>Pilih Processor</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="input-select component-select" id="processor-select"
                                            data-component="processor">
                                            <option value="">Pilih Socket terlebih dahulu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="qty-input">
                                        <input type="number" class="input quantity-input" value="1" min="1"
                                            data-component="processor">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="input subtotal-input" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Motherboard Selection -->
                            <div class="form-section row align-items-center">
                                <div class="col-md-3">
                                    <label>Pilih Motherboard</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="input-select component-select" id="motherboard-select"
                                            data-component="motherboard">
                                            <option value="">Pilih Processor terlebih dahulu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="qty-input">
                                        <input type="number" class="input quantity-input" value="1" min="1"
                                            data-component="motherboard">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="input subtotal-input" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- RAM Selection -->
                            <div class="form-section row align-items-center">
                                <div class="col-md-3">
                                    <label>Pilih RAM</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="input-select component-select" id="ram-select" data-component="ram">
                                            <option value="">Pilih Motherboard terlebih dahulu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="qty-input">
                                        <input type="number" class="input quantity-input" name="quantity" value="1"
                                            min="1" data-component="ram">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="input subtotal-input" name="subtotal" readonly>
                                    </div>
                                </div>
                            </div>

                            @foreach ($kategori as $k)
                                <h3 class="section-title">{{ Str::upper($k->nama) }}</h3>
                                @foreach ($k->children()->where('tipe', 'general')->get() as $item)
                                    <div class="form-section row align-items-center">
                                        <div class="col-md-3">
                                            <label>Pilih {{ Str::upper($item->nama) }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="input-select component-select"
                                                    data-component="{{ Str::slug($item->nama) }}">
                                                    <option value="">Pilih {{ $item->nama }}</option>
                                                    @foreach ($item->produk as $produk)
                                                        <option value="{{ $produk->id }}"
                                                            data-harga="{{ $produk->harga_setelah_diskon }}">
                                                            {{ $produk->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="qty-input">
                                                <input type="number" class="input quantity-input" value="1"
                                                    min="1" data-component="{{ Str::slug($item->nama) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <input type="text" class="input subtotal-input" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                            <hr>

                            <div class="form-section row align-item-right">
                                <div class="col-md-3"></div>
                                <div class="col-md-4 text-right">
                                    <label for="grandTotal">Grand Total</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group float-right" style="width: 100%;">
                                        <input type="text" class="input" name="grandTotal" readonly>
                                    </div>
                                </div>
                            </div>
                            <button id="save-build" class="btn btn-primary mt-3">
                                Simpan Rakitan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('front-script')
    <script>
        $(document).ready(function() {
            // Inisialisasi dengan data awal
            const initialData = @json($initialData);
            let currentMode = $('#mode-select').val();
            let selectedComponents = {};

            // Inisialisasi select berdasarkan mode saat ini
            initializeSelects();

            // Keadaan awal - mode kompatibilitas
            if (currentMode === 'compatibility') {
                $('#socket-select, #processor-select, #motherboard-select, #ram-select').prop('disabled', true);
            }

            // Fungsi perhitungan harga
            function changePrice(componentType) {
                const section = $(`.form-section:has([data-component="${componentType}"])`);
                const select = section.find('.component-select');
                const quantity = section.find('.quantity-input').val();
                const price = select.find('option:selected').data('harga') || 0;
                const subtotal = price * quantity;
                let grandTotal = 0;

                $('.subtotal-input').each(function() {
                    const subtotalValue = parseFloat($(this).val().replace(/\./g, "").replace(/[^0-9.-]+/g,
                        "")) / 100;
                    if (!isNaN(subtotalValue)) {
                        grandTotal += subtotalValue;
                    }
                });
                console.log('Grandtotal:', grandTotal);
                $('input[name=grandTotal]').val(grandTotal);
                section.find('.subtotal-input').val(formatCurrency(subtotal));
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(amount);
            }

            // Inisialisasi select berdasarkan mode
            function initializeSelects() {
                if (currentMode === 'free') {
                    loadAllComponents();
                } else {
                    // Inisialisasi dengan opsi kosong untuk mode kompatibilitas
                    $('#socket-select').html('<option value="">Pilih Socket</option>');
                    $('#processor-select').html('<option value="">Pilih Processor</option>');
                    $('#motherboard-select').html('<option value="">Pilih Motherboard</option>');
                    $('#ram-select').html('<option value="">Pilih RAM</option>');
                }
            }

            // Memuat semua komponen untuk mode bebas
            async function loadAllComponents() {
                try {
                    // Memuat semua processor
                    const procResponse = await $.ajax({
                        url: `/simulasi/api/components/processor?mode=free`,
                        method: 'GET'
                    });
                    let procOptions = '<option value="">Pilih Processor</option>';
                    procResponse.forEach(proc => {
                        procOptions +=
                            `<option value="${proc.id}" data-harga="${proc.harga_setelah_diskon}">${proc.nama}</option>`;
                    });
                    $('#processor-select').html(procOptions).prop('disabled', false);

                    // Memuat semua motherboard
                    const moboResponse = await $.ajax({
                        url: `/simulasi/api/components/motherboard?mode=free`,
                        method: 'GET'
                    });
                    let moboOptions = '<option value="">Pilih Motherboard</option>';
                    moboResponse.forEach(mobo => {
                        moboOptions +=
                            `<option value="${mobo.id}" data-harga="${mobo.harga_setelah_diskon}">${mobo.nama}</option>`;
                    });
                    $('#motherboard-select').html(moboOptions).prop('disabled', false);

                    // Memuat semua RAM
                    const ramResponse = await $.ajax({
                        url: `/simulasi/api/components/ram?mode=free`,
                        method: 'GET'
                    });
                    let ramOptions = '<option value="">Pilih RAM</option>';
                    ramResponse.forEach(ram => {
                        ramOptions +=
                            `<option value="${ram.id}" data-harga="${ram.harga_setelah_diskon}">${ram.nama}</option>`;
                    });
                    $('#ram-select').html(ramOptions).prop('disabled', false);

                    // Juga mengaktifkan select socket di mode bebas dengan semua opsi
                    let socketOptions = '<option value="">Pilih Socket</option>';
                    initialData.sockets.forEach(socket => {
                        socketOptions += `<option value="${socket.id}">${socket.nama}</option>`;
                    });
                    $('#socket-select').html(socketOptions).prop('disabled', false);

                } catch (error) {
                    console.error('Error memuat komponen bebas:', error);
                }
            }

            // Handler pemilihan mode
            $('#mode-select').change(function() {
                currentMode = $(this).val();
                if (currentMode === 'free') {
                    // Mengaktifkan semua select dan menampilkan semua opsi
                    $('#brand-select').prop('disabled', false).val('');
                    $('#socket-select, #processor-select, #motherboard-select, #ram-select').prop(
                        'disabled', false);
                    loadAllComponents();
                } else {
                    // Mode kompatibilitas - reset dan nonaktifkan field
                    $('#brand-select').prop('disabled', false).val('');
                    $('#socket-select').val('').prop('disabled', true);
                    $('#processor-select').val('').prop('disabled', true);
                    $('#motherboard-select').val('').prop('disabled', true);
                    $('#ram-select').val('').prop('disabled', true);
                }

                // Reset semua pilihan
                selectedComponents = {};
                $('#save-build').prop('disabled', true);
            });

            // Perubahan Brand Select - hanya relevan di mode kompatibilitas
            $('#brand-select').change(function() {
                if (currentMode === 'compatibility') {
                    const brandId = $(this).val();
                    if (brandId) {
                        loadSockets(brandId);
                    } else {
                        $('#socket-select').val('').prop('disabled', true);
                        $('#processor-select').val('').prop('disabled', true);
                    }
                }
            });

            // Perubahan Socket Select - hanya relevan di mode kompatibilitas
            $('#socket-select').change(function() {
                if (currentMode === 'compatibility') {
                    const socketId = $(this).val();
                    if (socketId) {
                        loadProcessors(socketId);
                    } else {
                        $('#processor-select').val('').prop('disabled', true);
                    }
                }
            });

            // Pemilihan Processor
            $('#processor-select').change(function() {
                const processorId = $(this).val();
                selectedComponents.processor = processorId;
                changePrice('processor');

                if (currentMode === 'compatibility') {
                    const socketId = $('#socket-select').val();
                    if (socketId && processorId) {
                        loadMotherboards(socketId);
                    } else {
                        $('#motherboard-select').val('').prop('disabled', true);
                    }
                }
            });

            // Pemilihan Motherboard
            $('#motherboard-select').change(function() {
                const moboId = $(this).val();
                selectedComponents.motherboard = moboId;
                changePrice('motherboard');

                if (currentMode === 'compatibility') {
                    if (moboId) {
                        loadRAM(moboId);
                    } else {
                        $('#ram-select').val('').prop('disabled', true);
                    }
                }
            });

            // Pemilihan RAM
            $('#ram-select').change(function() {
                selectedComponents.ram = $(this).val();
                changePrice('ram');
                $('#save-build').prop('disabled', false);
            });

            // Handler perubahan komponen untuk kategori dinamis
            $(document).on('change', '.component-select', function() {
                const componentType = $(this).data('component');
                changePrice(componentType);
            });

            $(document).on('input', '.quantity-input', function() {
                const componentType = $(this).data('component');
                changePrice(componentType);
            });

            // Fungsi ajax untuk mode kompatibilitas
            async function loadSockets(brandId) {
                try {
                    const response = await $.ajax({
                        url: `/simulasi/api/sockets?brand_id=${brandId}`,
                        method: 'GET'
                    });
                    let options = '<option value="">Pilih Socket</option>';
                    response.forEach(socket => {
                        options += `<option value="${socket.id}">${socket.nama}</option>`;
                    });
                    $('#socket-select').html(options).prop('disabled', false);
                    $('#processor-select, #motherboard-select, #ram-select').val('').prop('disabled', true);
                } catch (error) {
                    console.error('Error memuat socket:', error);
                }
            }

            async function loadProcessors(socketId) {
                try {
                    const response = await $.ajax({
                        url: `/simulasi/api/components/processor?mode=${currentMode}&socket_id=${socketId}`,
                        method: 'GET'
                    });
                    let options = '<option value="">Pilih Processor</option>';
                    response.forEach(proc => {
                        options +=
                            `<option value="${proc.id}" data-harga="${proc.harga_setelah_diskon}">${proc.nama}</option>`;
                    });

                    $('#processor-select').html(options).prop('disabled', false);
                    $('#motherboard-select, #ram-select').val('').prop('disabled', true);
                } catch (error) {
                    console.error('Error memuat processor:', error);
                }
            }

            async function loadMotherboards(socketId) {
                try {
                    const response = await $.ajax({
                        url: `/simulasi/api/components/motherboard?mode=${currentMode}&socket_id=${socketId}`,
                        method: 'GET'
                    });
                    let options = '<option value="">Pilih Motherboard</option>';
                    response.forEach(mobo => {
                        options +=
                            `<option value="${mobo.id}" data-harga="${mobo.harga_setelah_diskon}">${mobo.nama}</option>`;
                    });

                    $('#motherboard-select').html(options).prop('disabled', false);
                    $('#ram-select').val('').prop('disabled', true);
                } catch (error) {
                    console.error('Error memuat motherboard:', error);
                }
            }

            async function loadRAM(motherboardId) {
                try {
                    const response = await $.ajax({
                        url: `/simulasi/api/components/ram?mode=${currentMode}&motherboard_id=${motherboardId}`,
                        method: 'GET'
                    });
                    let options = '<option value="">Pilih RAM</option>';
                    response.forEach(ram => {
                        options +=
                            `<option value="${ram.id}" data-harga="${ram.harga_setelah_diskon}">${ram.nama}</option>`;
                    });

                    $('#ram-select').html(options).prop('disabled', false);
                } catch (error) {
                    console.error('Error memuat RAM:', error);
                }
            }

            async function saveBuild() {
                if (!checkAuth()) return;
                try {

                    const buildData = {
                        mode: currentMode,
                        components: {},
                        description: 'Rakitan PC saya',
                        is_public: false
                    };

                    // Tambahkan komponen utama
                    ['processor', 'motherboard', 'ram'].forEach(type => {
                        const select = $(`#${type}-select`);
                        const quantity = $(`input[data-component="${type}"]`).val();
                        if (select.val()) {
                            buildData.components[type] = {
                                id: select.val(),
                                quantity: quantity
                            };
                        }
                    });


                    $('.component-select').each(function() {
                        const componentType = $(this).data('component');
                        if (!['processor', 'motherboard', 'ram'].includes(componentType)) {
                            const quantity = $(`input[data-component="${componentType}"]`).val();
                            if ($(this).val()) {
                                buildData.components[componentType] = {
                                    id: $(this).val(),
                                    quantity: quantity
                                };
                            }
                        }
                    });

                    const response = await ajaxRequest({
                        url: '/simulasi/save',
                        method: 'POST',
                        data: buildData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    if (response.success) {
                        alert(response.message);
                        // window.location.href = '/simulasi/list'; // Redirect ke daftar rakitan
                        window.location.href = '/simulasi'
                    } else {
                        throw new Error(response.message);
                    }
                } catch (error) {
                    console.error('Error menyimpan rakitan:', error);
                    alert('Gagal menyimpan rakitan: ' + error.message);
                }
            }

            $('#save-build').click(function(e) {
                e.preventDefault();
                saveBuild();
            });
        });
    </script>
@endpush
