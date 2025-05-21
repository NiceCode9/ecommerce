@extends('admin.layouts.app', ['title' => 'Create Rekomendasi Rakitan'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        @csrf
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="kategoriBuild-select">Kategori Rakitan</label>
                            <select class="form-control" id="kategoriBuild-select" required>
                                <option value="">Pilih Kategori Rakitan</option>
                                @foreach ($kategoriBuilds as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand-select">Brands</label>
                            <select class="form-control" id="brand-select" required>
                                <option value="">Pilih Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="socket-select">Sockets</label>
                            <select class="form-control" id="socket-select">
                                <option value="">Pilih Socket terlebih dahulu</option>
                                @foreach ($sockets as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3 row form-section">
                            <div class="col-md-3">
                                <label for="socket-select">Processor</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control component-select" id="processor-select"
                                    data-component="processor">
                                    <option value="">Pilih Processor terlebih dahulu</option>
                                    @foreach ($processors as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga_setelah_diskon }}">
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control quantity" value="1" min="1"
                                    data-component="processor">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control subtotal" data-component="processor" readonly>
                            </div>
                        </div>
                        <div class="form-group row mt-3 form-section">
                            <div class="col-md-3">
                                <label for="socket-select">Motherboard</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control component-select" id="motherboard-select"
                                    data-component="motherboard">
                                    <option value="">Pilih Motherboard terlebih dahulu</option>
                                    @foreach ($motherboards as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga_setelah_diskon }}">
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control quantity" value="1" min="1"
                                    data-component="motherboard">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control subtotal" data-component="motherboard" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4 form-section">
                            <div class="col-md-3">
                                <label for="socket-select">Memory</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control component-select" id="ram-select" data-component="ram">
                                    <option value="">Pilih Memory terlebih dahulu</option>
                                    @foreach ($rams as $item)
                                        <option value="{{ $item->id }}"
                                            data-harga="{{ $item->harga_setelah_diskon }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control quantity" value="1" min="1"
                                    data-component="ram">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control subtotal" data-component="ram" readonly>
                            </div>
                        </div>
                        @foreach ($kategori as $k)
                            <div class="mb-3">
                                <h5 class="font-weight-bold">{{ Str::upper($k->nama) }}</h3>
                                    @foreach ($k->children()->where('tipe', 'general')->get() as $item)
                                        <div class="row form-section align-items-center">
                                            <div class="col-md-3">
                                                <label>Pilih {{ Str::upper($item->nama) }}</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control component-select"
                                                        data-component="{{ Str::slug($item->nama) }}">
                                                        <option value="">Pilih {{ $item->nama }}</option>
                                                        @foreach ($item->produk as $p)
                                                            <option value="{{ $p->id }}"
                                                                data-harga="{{ $p->harga }}">
                                                                {{ $p->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 form-group">
                                                <input type="number" class="form-control quantity"
                                                    data-component="{{ Str::slug($item->nama) }}" min="1"
                                                    value="1">
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <input type="text" class="form-control subtotal"
                                                    data-component="{{ Str::slug($item->nama) }}" readonly>
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
                        <button id="save-build" class="btn btn-primary mt-3">
                            Simpan Rakitan
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
            async function changePrice(componentType) {
                const section = $(`.form-section:has([data-component="${componentType}"])`);
                const select = section.find('.component-select');
                const quantity = section.find('.quantity').val();
                const price = select.find('option:selected').data('harga') || 0;
                const subtotal = price * quantity;

                // Set the subtotal for this component before calculating grand total
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

            async function save() {
                try {
                    if ($('#description').val() == '' || $('#brand-select').find('option:selected').val() ==
                        '' | $(
                            '#socket-select').find('option:selected').val() == '') {
                        alert('Lengkapi data terlebih dahulu');
                        return false;
                    }

                    const buildData = {
                        mode: 'free',
                        components: {},
                        description: $('#description').val(),
                        brand_id: $('#brand-select').find('option:selected').val(),
                        socket_id: $('#socket-select').find('option:selected').val(),
                        kategoriBuild_id: $('#kategoriBuild-select').find('option:selected').val(),
                        total_price: $('input[name=grandTotal]').val(),
                    };

                    ['processor', 'motherboard', 'ram'].forEach(type => {
                        const select = $(`#${type}-select`);
                        const quantity = $(`input[data-component="${type}"].quantity`).val();
                        const subtotal = $(`input[data-component="${type}"].subtotal`).val();
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
                            console.log(componentType);
                            const quantity = $(`input[data-component="${componentType}"].quantity`)
                                .val();
                            const subtotal = $(`input[data-component="${componentType}"].subtotal`)
                                .val();
                            if ($(this).val()) {
                                buildData.components[componentType] = {
                                    id: $(this).val(),
                                    quantity: quantity,
                                    subtotal: subtotal,
                                };
                            }
                        }
                    });
                    console.log(buildData);

                    const response = await ajaxRequest({
                        url: '/admin/rakitan',
                        method: 'POST',
                        data: buildData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Data berhasil disimpan');
                                window.location.href =
                                    "{{ route('admin.rakit.index') }}";
                            } else {
                                console.log(response.message);
                                window.location.reload();
                            }
                        }
                    });
                } catch (error) {
                    console.log(error);
                }
            }

            $('#save-build').click(function(e) {
                e.preventDefault();
                save();
            });
        });
    </script>
@endpush
