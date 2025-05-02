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
                                <hr>
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

                            <button id="save-build" class="btn btn-primary mt-3" disabled>
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
            // Initialize with initial data
            const initialData = @json($initialData);
            let currentMode = $('#mode-select').val();
            let selectedComponents = {};

            // Initialize selects based on current mode
            initializeSelects();

            // Initial state - compatibility mode
            if (currentMode === 'compatibility') {
                $('#socket-select, #processor-select, #motherboard-select, #ram-select').prop('disabled', true);
            }

            // Price calculation functions
            function changePrice(componentType) {
                const section = $(`.form-section:has([data-component="${componentType}"])`);
                const select = section.find('.component-select');
                const quantity = section.find('.quantity-input').val();
                const price = select.find('option:selected').data('harga') || 0;
                const subtotal = price * quantity;

                section.find('.subtotal-input').val(formatCurrency(subtotal));
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(amount);
            }

            // Initialize selects based on mode
            function initializeSelects() {
                if (currentMode === 'free') {
                    loadAllComponents();
                } else {
                    // Initialize with empty options for compatibility mode
                    $('#socket-select').html('<option value="">Pilih Socket</option>');
                    $('#processor-select').html('<option value="">Pilih Processor</option>');
                    $('#motherboard-select').html('<option value="">Pilih Motherboard</option>');
                    $('#ram-select').html('<option value="">Pilih RAM</option>');
                }
            }

            // Load all components for free mode
            async function loadAllComponents() {
                try {
                    // Load all processors
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

                    // Load all motherboards
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

                    // Load all RAM
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

                    // Also enable socket select in free mode with all options
                    let socketOptions = '<option value="">Pilih Socket</option>';
                    initialData.sockets.forEach(socket => {
                        socketOptions += `<option value="${socket.id}">${socket.nama}</option>`;
                    });
                    $('#socket-select').html(socketOptions).prop('disabled', false);

                } catch (error) {
                    console.error('Error loading free components:', error);
                }
            }

            // Mode selection handler
            $('#mode-select').change(function() {
                currentMode = $(this).val();
                if (currentMode === 'free') {
                    // Enable all selects and show all options
                    $('#brand-select').prop('disabled', false).val('');
                    $('#socket-select, #processor-select, #motherboard-select, #ram-select').prop(
                        'disabled', false);
                    loadAllComponents();
                } else {
                    // Compatibility mode - reset and disable fields
                    $('#brand-select').prop('disabled', false).val('');
                    $('#socket-select').val('').prop('disabled', true);
                    $('#processor-select').val('').prop('disabled', true);
                    $('#motherboard-select').val('').prop('disabled', true);
                    $('#ram-select').val('').prop('disabled', true);
                }

                // Reset all selections
                selectedComponents = {};
                $('#save-build').prop('disabled', true);
            });

            // Brand Select Change - only relevant in compatibility mode
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

            // Socket Select Change - only relevant in compatibility mode
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

            // Processor Select Change
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

            // Motherboard Select Change
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

            // RAM Select Change
            $('#ram-select').change(function() {
                selectedComponents.ram = $(this).val();
                changePrice('ram');
                $('#save-build').prop('disabled', false);
            });

            // Component change handlers for dynamic categories
            $(document).on('change', '.component-select', function() {
                const componentType = $(this).data('component');
                changePrice(componentType);
            });

            $(document).on('input', '.quantity-input', function() {
                const componentType = $(this).data('component');
                changePrice(componentType);
            });

            // AJAX functions for compatibility mode
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
                    console.error('Error loading sockets:', error);
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
                    console.error('Error loading processors:', error);
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
                    console.error('Error loading motherboards:', error);
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
                    console.error('Error loading RAM:', error);
                }
            }

            // Save build function
            async function saveBuild() {
                try {
                    // Collect all selected components and quantities
                    const buildData = {
                        mode: currentMode,
                        components: {
                            processor: {
                                id: $('#processor-select').val(),
                                quantity: $(`input[data-component="processor"]`).val()
                            },
                            motherboard: {
                                id: $('#motherboard-select').val(),
                                quantity: $(`input[data-component="motherboard"]`).val()
                            },
                            ram: {
                                id: $('#ram-select').val(),
                                quantity: $(`input[data-component="ram"]`).val()
                            }
                        }
                    };

                    // Add dynamic category components
                    $('.component-select').each(function() {
                        const componentType = $(this).data('component');
                        if (componentType !== 'processor' && componentType !== 'motherboard' &&
                            componentType !== 'ram') {
                            buildData.components[componentType] = {
                                id: $(this).val(),
                                quantity: $(`input[data-component="${componentType}"]`).val()
                            };
                        }
                    });

                    const response = await $.ajax({
                        url: '/simulasi/save',
                        method: 'POST',
                        data: buildData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    alert('Rakitan berhasil disimpan!');
                    window.location.href = '/simulasi/list'; // Redirect to build list
                } catch (error) {
                    console.error('Error saving build:', error);
                    alert('Gagal menyimpan rakitan: ' + error.responseJSON.message);
                }
            }

            // Save build button click handler
            $('#save-build').click(function(e) {
                e.preventDefault();
                saveBuild();
            });
        });
    </script>
@endpush
