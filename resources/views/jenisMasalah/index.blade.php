<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jenis Masalah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Jenis
                        Masalah</button>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Masalah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="jenisMasalahTable">
                            @foreach ($jenisMasalah as $masalah)
                                <tr id="row-{{ $masalah->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $masalah->nama_masalah }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editBtn" data-id="{{ $masalah->id }}"
                                            data-nama="{{ $masalah->nama_masalah }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $masalah->id }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="createForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Tambah Jenis Masalah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_masalah" class="form-label">Nama Masalah</label>
                            <input type="text" class="form-control" id="nama_masalah" name="nama_masalah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Jenis Masalah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_masalah" class="form-label">Nama Masalah</label>
                            <input type="text" class="form-control" id="edit_nama_masalah" name="nama_masalah"
                                required>
                            <input type="hidden" id="edit_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Buka Modal secara eksplisit menggunakan JavaScript
        $(document).ready(function() {
            $('[data-bs-toggle="modal"]').on('click', function() {
                var targetModal = $(this).data('bs-target');
                var modal = new bootstrap.Modal(document.querySelector(targetModal));
                modal.show();
            });
        });
    </script>

    <script>
        // Tambah Data
        $('#createForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.post("{{ route('jenisMasalah.store') }}", formData, function(response) {
                if (response.success) {
                    let newRow = `
                        <tr id="row-${response.id}">
                            <td>${response.id}</td>
                            <td>${response.nama_masalah}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editBtn" 
                                    data-id="${response.id}" 
                                    data-nama="${response.nama_masalah}">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm deleteBtn" 
                                    data-id="${response.id}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#jenisMasalahTable').append(newRow); // Menambahkan baris baru ke tabel
                    $('#createModal').modal('hide'); // Sembunyikan modal
                } else {
                    alert('Gagal menambah data');
                }
            });
        });

        // Tampilkan Modal Edit
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            $('#edit_id').val(id);
            $('#edit_nama_masalah').val(nama);
            $('#editModal').modal('show');
        });

        // Update Data
        $('#editForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let formData = $(this).serialize();

            $.ajax({
                url: `/jenisMasalah/${id}`,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#row-' + id).find('td').eq(1).text(response.nama_masalah);
                        $('#editModal').modal('hide'); // Tutup modal setelah update
                    } else {
                        alert('Gagal memperbarui data');
                    }
                }
            });
        });

        // Hapus Data
        $('.deleteBtn').click(function() {
            let id = $(this).data('id');

            if (confirm('Yakin ingin menghapus?')) {
                $.ajax({
                    url: `/jenisMasalah/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#row-' + id).remove(); // Hapus baris tabel yang sesuai
                        } else {
                            alert('Gagal menghapus data');
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
