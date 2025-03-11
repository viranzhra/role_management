<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                        @can('create siswa')
                            <button id="btnAddSiswa" class="btn btn-primary">Tambah Siswa</button>
                        @endcan
                    </div>

                    <table class="table table-bordered" id="siswaTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables akan mengisi data di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addSiswaModal" tabindex="-1" aria-labelledby="addSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addSiswaForm" method="POST" action="{{ route('siswa.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSiswaLabel">Tambah Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Siswa</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="" disabled selected>Pilih Siswa</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" name="nisn" id="nisn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control">
                                <option value="" disabled selected>Pilih Kelas</option>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                                @endforeach
                            </select>
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
    <div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editSiswaForm" method="POST" action="{{ route('siswa.update', ':id') }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSiswaLabel">Edit Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_user_id" class="form-label">Siswa</label>
                            <input type="text" id="edit_user_id" name="edit_user_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nisn" class="form-label">NISN</label>
                            <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="edit_kelas_id" class="form-control">
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Add/Edit -->
    {{-- <div class="modal fade" id="siswaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="siswaModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="siswaForm">
                        @csrf
                        <input type="hidden" id="siswaId" name="id">
                        <div class="form-group">
                            <label for="name">Nama Siswa</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" id="nisn" name="nisn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <select id="kelas" name="kelas_id" class="form-control" required>
                                <option value="" disabled>Pilih Kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="btnSaveSiswa" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div> --}}

    <script>
        $(function () {
            let siswaTable = $('#siswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siswa.getData') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'user.name' }, // Ambil data nama dari relasi user
                    { data: 'nisn', name: 'nisn' },
                    { data: 'kelas', name: 'kelas.nama_kelas' }, // Ambil nama kelas dari relasi kelas
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Tambah Siswa
            $('#btnAddSiswa').on('click', function () {
                $('#siswaForm')[0].reset();
                $('#kelas').val(''); // Reset dropdown
                $('#siswaModalLabel').text('Tambah Siswa');
                $('#siswaId').val('');
                $('#siswaModal').modal('show');
            });

            // Simpan Data
            $('#addSiswaForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        // Reload DataTable
                        $('#siswaTable').DataTable().ajax.reload();
                        $('#addSiswaModal').modal('hide');
                    },
                    error: function(error) {
                        // Handle error
                        alert('Gagal menambah data siswa');
                    }
                });
            });

            // $('#btnSaveSiswa').on('click', function () {
            //     let siswaId = $('#siswaId').val();
            //     let url = siswaId ? "{{ route('siswa.update', ':id') }}".replace(':id', siswaId) : "{{ route('siswa.store') }}";
            //     let method = siswaId ? 'PUT' : 'POST';

            //     $.ajax({
            //         url: url,
            //         type: method,
            //         data: $('#siswaForm').serialize(),
            //         success: function (response) {
            //             $('#siswaModal').modal('hide');
            //             siswaTable.ajax.reload();
            //             alert(response.message);
            //         }
            //     });
            // });

            // Edit Siswa
            $(document).on('click', '.btn-edit', function() {
                var siswaId = $(this).data('id');
                var siswaName = $(this).data('name');
                var nisn = $(this).data('nisn');
                var kelasId = $(this).data('kelas_id');
                
                // Set value ke input modal
                $('#edit_id').val(siswaId);
                $('#edit_user_id').val(siswaName);
                $('#edit_nisn').val(nisn);
                $('#edit_kelas_id').val(kelasId);
                
                // Update form action URL
                var url = '/siswa/' + siswaId;
                $('#editSiswaForm').attr('action', url);
                
                // Show modal
                $('#editSiswaModal').modal('show');
            });

            // $(document).on('click', '.btn-edit', function () {
            //     let siswa = $(this).data();
            //     $('#siswaId').val(siswa.id);
            //     $('#name').val(siswa.name);
            //     $('#nisn').val(siswa.nisn);
            //     $('#kelas').val(siswa.nama_kelas).change(); // Pilih kelas sesuai data
            //     $('#siswaModalLabel').text('Edit Siswa');
            //     $('#siswaModal').modal('show');
            // });

            // Hapus Siswa
            $(document).on('click', '.btn-delete', function() {
                var siswaId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus siswa ini?')) {
                    $.ajax({
                        url: '/siswa/' + siswaId, // Ganti dengan URL yang sesuai
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Pastikan token CSRF ada
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.success); // Tampilkan pesan sukses
                                $('#siswaTable').DataTable().ajax.reload(); // Reload data tabel tanpa reload halaman
                            } else {
                                alert('Terjadi kesalahan saat menghapus data!');
                            }
                        },
                        error: function(xhr) {
                            alert('Gagal menghapus data siswa!');
                        }
                    });
                }
            });

            // $(document).on('click', '.btn-delete', function () {
            //     if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            //         let url = "{{ route('siswa.destroy', ':id') }}".replace(':id', $(this).data('id'));

            //         $.ajax({
            //             url: url,
            //             type: 'DELETE',
            //             success: function (response) {
            //                 siswaTable.ajax.reload();
            //                 alert(response.message);
            //             }
            //         });
            //     }
            // });
        });
    </script>
</x-app-layout>
