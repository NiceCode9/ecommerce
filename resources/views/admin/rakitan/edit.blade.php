@extends('admin.layouts.app', ['title' => 'Edit Rekomendasi Rakitan'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" rows="5" class="form-control">{{ $build->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="kategoriBuild-select">Kategori Rakitan</label>
                            <select class="form-control" id="kategoriBuild-select" required>
                                <option value="">Pilih Kategori Rakitan</option>
                                @foreach ($kategoriBuilds as $k)
                                    <option value="{{ $k->id }}"
                                        {{ $build->kategori_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand-select">Brands</label>
                            <select class="form-control" id="brand-select" required>
                                <option value="">Pilih Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ $build->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="socket-select">Sockets</label>
                            <select class="form-control" id="socket-select">
                                <option value="">Pilih Socket</option>
                                @foreach ($sockets as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $build->socket_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-3 row form-section">
                            <div class="col-md-3">
                                <label>Processor</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control component-select" data-component="processor">
                                    <option value="">Pilih Processor</option>
                                    @foreach ($processors as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga_setelah_diskon }}"
                                            {{ $build->components->where('component_type', 'processor')->first()?->produk_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control quantity"
                                    value="{{ $build->components->where('component_type', 'processor')->first()?->quantity ?? 1 }}"
                                    min="1" data-component="processor">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control subtotal" data-component="processor" readonly
                                    value="{{ $build->components->where('component_type', 'processor')->first()?->subtotal ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group row mt-3 form-section">
                            <div class="col-md-3">
                                <label>Motherboard</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control component-select" data-component="motherboard">
                                    <option value="">Pilih Motherboard</option>
                                    @foreach ($motherboards as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga_setelah_diskon }}"
                                            {{ $build->components->where('component_type', 'motherboard')->first()?->produk_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control quantity"
                                    value="{{ $build->components->where('component_type', 'motherboard')->first()?->quantity ?? 1 }}"
                                    min="1" data-component="motherboard">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control subtotal" data-component="motherboard" readonly
                                    value="{{ $build->components->where('component_type', 'motherboard')->first()?->subtotal ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4 form-section">
                            <div class="col-md-3">
                                <label>Memory</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control component-select" data-component="ram">
                                    <option value="">Pilih Memory</option>
                                    @foreach ($rams as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga_setelah_diskon }}"
                                            {{ $build->components->where('component_type', 'ram')->first()?->produk_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control quantity"
                                    value="{{ $build->components->where('component_type', 'ram')->first()?->quantity ?? 1 }}"
                                    min="1" data-component="ram">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control subtotal" data-component="ram" readonly
                                    value="{{ $build->components->where('component_type', 'ram')->first()?->subtotal ?? 0 }}">
                            </div>
                        </div>

                        @foreach ($kategori as $k)
                            <div class="mb-3">
                                <h5 class="font-weight-bold">{{ Str::upper($k->nama) }}</h5>
                                @foreach ($k->children()->where('tipe', 'general')->get() as $item)
                                    <div class="row section-form align-items-center">
                                        <div class="col-md-3">
                                            <label>{{ Str::upper($item->nama) }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control component-select"
                                                    data-component="{{ Str::slug($item->nama) }}">
                                                    <option value="">Pilih {{ $item->nama }}</option>
                                                    @foreach ($item->produk as $produk)
                                                        <option value="{{ $produk->id }}"
                                                            data-harga="{{ $produk->harga_setelah_diskon }}"
                                                            {{ $build->components->where('component_type', Str::slug($item->nama))->first()?->produk_id == $produk->id ? 'selected' : '' }}>
                                                            {{ $produk->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 form-group">
                                            <input type="number" class="form-control quantity"
                                                value="{{ $build->components->where('component_type', Str::slug($item->nama))->first()?->quantity ?? 1 }}"
                                                min="1" data-component="{{ Str::slug($item->nama) }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <input type="text" class="form-control subtotal"
                                                data-component="{{ Str::slug($item->nama) }}" readonly
                                                value="{{ $build->components->where('component_type', Str::slug($item->nama))->first()?->subtotal ?? 0 }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="mt-3">
                            <div class="row align-item-right">
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
                        </div>

                        <button id="update-build" class="btn btn-primary mt-3">
                            Update Rakitan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize existing components
            const existingComponents = @json($build->components->keyBy('component_type'));

            // Set initial values for components
            Object.entries(existingComponents).forEach(([type, component]) => {
                $(`.component-select[data-component="${type}"]`).val(component.produk_id);
                $(`.quantity[data-component="${type}"]`).val(component.quantity);
                $(`.subtotal[data-component="${type}"]`).val(component.subtotal);
            });

            function changePrice(componentType) {
                const section = $(`.form-section:has([data-component="${componentType}"])`);
                const select = section.find('.component-select');
                const quantity = section.find('.quantity').val();
                const price = select.find('option:selected').data('harga') || 0;
                const subtotal = price * quantity;

                section.find('.subtotal').val(subtotal);

                let grandTotal = 0;
                $('.subtotal').each(function() {
                    const subtotalValue = parseFloat($(this).val()) || 0;
                    if (!isNaN(subtotalValue)) {
                        grandTotal += subtotalValue;
                    }
                });

                $('input[name=grandTotal]').val(grandTotal);
            }

            $(document).on('change', '.component-select', function() {
                const componentType = $(this).data('component');
                changePrice(componentType);
            });

            $(document).on('input', '.quantity', function() {
                const componentType = $(this).data('component');
                changePrice(componentType);
            });

            // Calculate initial grand total
            let initialGrandTotal = 0;
            $('.subtotal').each(function() {
                initialGrandTotal += parseFloat($(this).val()) || 0;
            });
            $('input[name=grandTotal]').val(initialGrandTotal);

            async function update() {
                try {
                    if ($('#description').val() == '' || $('#brand-select').val() == '' || $('#socket-select')
                        .val() == '') {
                        alert('Lengkapi data terlebih dahulu');
                        return false;
                    }

                    const buildData = {
                        mode: 'free',
                        components: {},
                        description: $('#description').val(),
                        brand_id: $('#brand-select').val(),
                        socket_id: $('#socket-select').val(),
                        kategoriBuild_id: $('#kategoriBuild-select').val(),
                        total_price: $('input[name=grandTotal]').val(),
                    };

                    // Collect components data
                    ['processor', 'motherboard', 'ram'].forEach(type => {
                        const select = $(`.component-select[data-component="${type}"]`);
                        const quantity = $(`.quantity[data-component="${type}"]`).val();
                        const subtotal = $(`.subtotal[data-component="${type}"]`).val();
                        if (select.val()) {
                            buildData.components[type] = {
                                id: select.val(),
                                quantity: quantity,
                                subtotal: subtotal,
                            };
                        }
                    });

                    $('.component-select').each(function() {
                        const componentType = $(this).data('component');
                        if (!['processor', 'motherboard', 'ram'].includes(componentType)) {
                            const quantity = $(`.quantity[data-component="${componentType}"]`).val();
                            const subtotal = $(`.subtotal[data-component="${componentType}"]`).val();
                            if ($(this).val()) {
                                buildData.components[componentType] = {
                                    id: $(this).val(),
                                    quantity: quantity,
                                    subtotal: subtotal,
                                };
                            }
                        }
                    });

                    const response = await ajaxRequest({
                        url: '/admin/rakitan/{{ $build->id }}',
                        method: 'PUT',
                        data: buildData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Data berhasil diupdate');
                                window.location.href = "{{ route('admin.rakit.index') }}";
                            }
                        }
                    });
                } catch (error) {
                    console.error(error);
                }
            }

            $('#update-build').click(function(e) {
                e.preventDefault();
                update();
            });
        });
    </script>
@endpush
