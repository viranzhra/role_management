<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <h3 class="text-lg font-semibold">Daftar Kelas</h3>
                        @can('create kelas')
                            <button id="btnAddKelas" class="btn btn-primary">Tambah Kelas</button>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="kelasTable" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Jurusan</th>
                                    <th class="action-column">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate data here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addKelasForm" method="POST" action="{{ route('kelas.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKelasLabel">Tambah Data Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Input Nama Kelas -->
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editKelasModal" tabindex="-1" aria-labelledby="editKelasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editKelasForm" method="POST" action="{{ route('kelas.update', ':id') }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKelasLabel">Edit Data Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="edit_nama_kelas" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let kelasTable = $('#kelasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kelas.getData') }}",
                columns: [
                    {
                        data: null, // Gunakan `null` untuk nomor urut
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // Hitung nomor urut berdasarkan index baris
                        }
                    },
                    {
                        data: 'nama_kelas',
                        name: 'nama_kelas'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan.jurusan_id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Cek apakah kolom Aksi memiliki data
            kelasTable.on('draw', function() {
                let actionColumnCells = $('#kelasTable tbody tr td:nth-child(3)');
                
                // Jika kolom aksi kosong, sembunyikan kolom sepenuhnya (termasuk garis)
                if (actionColumnCells.filter(function() { return $(this).text().trim() === ""; }).length === actionColumnCells.length) {
                    // Sembunyikan kolom Aksi secara keseluruhan
                    kelasTable.column(2).visible(false); // Kolom ketiga (Aksi)
                } else {
                    // Tampilkan kolom Aksi jika ada data
                    kelasTable.column(2).visible(true);
                }
            });

            // Tambah Kelas Modal
            $('#btnAddKelas').click(function() {
                $('#addKelasModal').modal('show');
            });

            // Tambah Kelas Submit
            $('#addKelasForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function() {
                        kelasTable.ajax.reload();
                        $('#addKelasModal').modal('hide');
                    },
                    error: function() {
                        alert('Gagal menambah data kelas.');
                    }
                });
            });

            // Edit Kelas
            $(document).on('click', '.btn-edit', function() {
                let kelas = $(this).data();

                // Assign data to modal fields
                $('#edit_id').val(kelas.id);
                $('#edit_nama_kelas').val(kelas.nama_kelas);

                // Set form action
                $('#editKelasForm').attr('action', "{{ url('kelas') }}/" + kelas.id);

                // Show the modal
                $('#editKelasModal').modal('show');
            });

            // Edit Kelas Submit
            $('#editKelasForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');
                const method = 'POST'; // Use POST, Laravel will handle PUT with @method('PUT')

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            kelasTable.ajax.reload();
                            $('#editKelasModal').modal('hide');
                        }
                    },
                    error: function(xhr) {
                        alert('Gagal: ' + (xhr.responseJSON.message || 'Terjadi kesalahan.'));
                    }
                });
            });

            // Hapus Kelas
            $(document).on('click', '.btn-delete', function() {
                var kelasId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
                    $.ajax({
                        url: '/kelas/' + kelasId,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.success);
                                $('#kelasTable').DataTable().ajax.reload();
                            } else {
                                alert('Terjadi kesalahan saat menghapus data!');
                            }
                        },
                        error: function() {
                            alert('Gagal menghapus data siswa!');
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
