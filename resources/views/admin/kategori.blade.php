@extends('admin.layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="alert alert-success" style="display: none;" id="success-message"></div>
    <div class="alert alert-danger" style="display: none;" id="error-message"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3 float-right"
                        data-target="#modal-tambah-kategori" data-toggle="modal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>

                    <div class="table-responsive">
                        <table class="table text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $k)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $k->nama_kategori }}</td>
                                        <td>{{ $k->slug }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btn-edit"
                                                data-id="{{ $k->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $k->id }}">Delete</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-tambah-kategori">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                placeholder="Masukkan Nama Kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Kategori"></textarea>
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
    <div class="modal fade" id="modal-edit-kategori" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-kategori">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
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
            // Create
            $('#form-tambah-kategori').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.kategori.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-tambah-kategori').modal('hide');
                        $('#success-message').text(response.message).show();
                        location.reload();
                    },
                    error: function(xhr) {
                        $('#error-message').text(xhr.responseJSON.message).show();
                    }
                });
            });

            // Edit - Load Data
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                let url = "{{ route('admin.kategori.edit', ':id') }}".replace(':id', id);
                $.get(`url`, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nama_kategori').val(data.nama);
                    $('#edit_deskripsi').val(data.deskripsi);
                    $('#modal-edit-kategori').modal('show');
                });
            });

            // Update
            $('#form-edit-kategori').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                let url = "{{ route('admin.kategori.update', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-edit-kategori').modal('hide');
                        $('#success-message').text(response.message).show();
                        location.reload();
                    },
                    error: function(xhr) {
                        $('#error-message').text(xhr.responseJSON.message).show();
                    }
                });
            });

            // Delete
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                if (confirm('Are you sure?')) {
                    let id = $(this).data('id');
                    let url = "{{ route('admin.kategori.destroy', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#success-message').text(response.message).show();
                            location.reload();
                        },
                        error: function(xhr) {
                            $('#error-message').text(xhr.responseJSON.message).show();
                        }
                    });
                }
            });
        });
    </script>
@endpush
