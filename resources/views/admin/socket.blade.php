@extends('admin.layouts.app', ['title' => 'Data Socket'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3 float-right"
                        data-target="#modal-tambah-socket" data-toggle="modal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Socket</th>
                                    <th>Brand</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sockets as $socket)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $socket->nama }}</td>
                                        <td>{{ $socket->brand->nama }}</td>
                                        <td>{{ $socket->deskripsi }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btn-edit"
                                                data-id="{{ $socket->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $socket->id }}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="modal-tambah-socket" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Socket</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-tambah-socket">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_socket" class="form-label">Nama Socket</label>
                            <input type="text" class="form-control" id="nama_socket" name="nama" required>
                            <small class="text-muted text-danger nama_error"></small>
                        </div>
                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->nama }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted text-danger brand_id_error"></small>
                        </div>
                        <div class="mb-32">
                            <label for="dekripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="modal-edit-socket" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Socket</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-socket" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id" value="">
                        <div class="mb-3">
                            <label for="edit_nama_socket" class="form-label">Nama Socket</label>
                            <input type="text" class="form-control" id="edit_nama_socket" name="nama" required>
                            <small class="text-muted text-danger nama_error_edit"></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_brand_id" class="form-label">Brand</label>
                            <select class="form-control" id="edit_brand_id" name="brand_id">
                                <small class="text-muted text-danger brand_id_error_edit"></small>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-32">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Create
            $('#form-tambah-socket').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.socket.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-tambah-socket').modal('hide');
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            allowOutsideClick: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // alert(xhr.responseJSON.message);
                        if (xhr.error == 422) {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {
                                $('small.' + key + '_error').text(value[0]);
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menyimpan data',
                                'error'
                            );
                            console.error(xhr.responseText);
                        }
                    }
                });
            });

            // Edit - Load Data
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                let url = "{{ route('admin.socket.edit', ':id') }}".replace(':id', id);
                $.get(url, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nama_socket').val(data.nama);
                    $('#edit_brand_id').val(data.brand_id);
                    $('#edit_deskripsi').val(data.deskripsi);
                    $('#modal-edit-socket').modal('show');
                });
            });

            // Update
            $('#form-edit-socket').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                let url = "{{ route('admin.socket.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-edit-socket').modal('hide');
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            allowOutsideClick: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, val) {
                                $('small.' + key + '_error_edit').text(val[0]);
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menyimpan data',
                                'error'
                            );
                            console.error(xhr.responseText);
                        }
                    }
                });
            });

            // Delete
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let url = "{{ route('admin.socket.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data akan dihapus secara permanen.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success",
                                    allowOutsideClick: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                alert(xhr.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
