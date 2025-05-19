@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3 float-right"
                        data-target="#modal-tambah-kategori" data-toggle="modal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>

                    <div class="table-responsive">
                        <table class="table table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Jumlah Rakitan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->build()->count() }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btn-edit"
                                                data-id="{{ $item->slug }}">Edit</button>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $item->slug }}">Delete</button>
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
    <div class="modal fade" id="modal-tambah-kategori" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Rakitan</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-tambah-kategori">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori Rakitan</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama" required>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modal-edit-kategori" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori Rakitan</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-kategori">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_kategori" class="form-label">Nama Brand</label>
                            <input type="text" class="form-control" id="edit_nama_kategori" name="nama" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
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
            $('#form-tambah-kategori').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.kategori-rakitan.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-tambah-brand').modal('hide');
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
                        alert(xhr.responseJSON.message);
                    }
                });
            });

            // Edit - Load Data
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                let url = "{{ route('admin.kategori-rakitan.edit', ':id') }}".replace(':id', id);
                $.get(url, function(data) {
                    console.log(data);

                    $('#edit_id').val(data.slug);
                    $('#edit_nama_kategori').val(data.nama);
                    $('#modal-edit-kategori').modal('show');
                });
            });

            // Update
            $('#form-edit-kategori').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                let url = "{{ route('admin.kategori-rakitan.update', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-edit-kategori').modal('hide');
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
                        alert(xhr.responseJSON.message);
                    }
                });
            });

            // Delete
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let url = "{{ route('admin.kategori-rakitan.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
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
