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
            {{ __('Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-3">

                        <button id="btnNaikKelas" class="btn btn-success">Naikkan Kelas</button>

                        <script>
                            $(document).ready(function() {
                                $('#btnNaikKelas').click(function() {
                                    if (confirm('Apakah Anda yakin ingin menaikkan semua siswa ke kelas berikutnya?')) {
                                        $.ajax({
                                            url: "{{ route('kelas.naikKelas') }}",
                                            type: "POST",
                                            data: {
                                                _token: "{{ csrf_token() }}"
                                            },
                                            success: function(response) {
                                                alert(response.message);
                                                location.reload();
                                            },
                                            error: function(xhr) {
                                                alert("Terjadi kesalahan saat menaikkan kelas.");
                                            }
                                        });
                                    }
                                });
                            });
                        </script>

                        @can('export siswa')
                            <!-- Form Export -->
                            <form action="{{ route('siswa.export') }}" method="GET" class="d-inline-block">
                                <div class="input-group">
                                    <select name="kelas_id" class="form-select">
                                        <option value="" selected>Semua Kelas</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-success">Export Excel</button>
                                </div>
                            </form>
                        @endcan

                        @can('import siswa')
                            <form id="formImport" class="d-inline-block">
                                @csrf
                                <input type="file" name="file" id="file"
                                    class="form-control d-inline-block w-auto">
                            </form>
                        @endcan
                        {{-- @can('import siswa')
                            <!-- Form Import -->
                            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                                @csrf
                                <input type="file" name="file" id="file" class="form-control d-inline-block w-auto">
                                <button type="submit" class="btn btn-primary">Import Excel</button>
                            </form>
                        @endcan --}}
                    </div>

                    @can('create siswa')
                        <button id="btnAddSiswa" class="btn btn-primary">Tambah Siswa</button>
                    @endcan

                    <!-- Preview Tabel Data dari Excel -->
                    <div id="previewContainer" style="display: none;">
                        <h5 class="mt-3 text-center">Preview Data</h5>
                        <p id="uploadedFileName" class="text-center"></p>
                        <table id="previewTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th id="errorColumn">Kesalahan</th> <!-- Kolom Kesalahan -->
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!-- Tombol Import hanya muncul jika tidak ada kesalahan -->
                        <div id="importButton" style="display: none;">
                            <button type="button" class="btn btn-primary" id="btnImport">Import Excel</button>
                        </div>
                    </div>
                    @if (auth()->user()->hasRole('Admin'))
                        <table class="table table-bordered" id="siswaTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    @else
                        <!-- Pesan jika tidak ada data siswa -->
                        <div id="noDataMessage" style="display: none;">
                            <p>Data siswa tidak ditemukan.</p>
                        </div>
                        <!-- Siswa: Detail Data -->
                        <div id="siswaDetail" style="display: none;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Nama Siswa</strong></div>:
                                        <div class="col-md-4">
                                            <span id="detailNama"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>NISN</strong></div>:
                                        <div class="col-md-4">
                                            <span id="detailNISN"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Kelas</strong></div>:
                                        <div class="col-md-4">
                                            <span id="detailKelas"></span>
                                        </div>
                                    </div>
                                    <!-- New row for total_poin -->
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Total Poin</strong></div>:
                                        <div class="col-md-4">
                                            <span id="detailTotalPoin"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                        <!-- Input Nama -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <!-- Input Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <!-- Input Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" name="password" id="password" class="form-control"
                                value="siswa123" readonly>
                            <small class="form-text text-muted">Password default adalah <strong>"siswa123"</strong>.
                                Siswa dapat mengubahnya setelah login.</small>
                        </div>
                        <!-- Input Konfirmasi Password -->
                        {{-- <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div> --}}
                        <!-- Input NISN -->
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" name="nisn" id="nisn" class="form-control" required>
                        </div>
                        <!-- Input Kelas -->
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_user_name" class="form-label">Siswa</label>
                            <input type="text" id="edit_user_name" class="form-control" name="user_id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nisn" class="form-label">NISN</label>
                            <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="edit_kelas_id" class="form-control">
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

    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk tabel preview
            const previewTable = $('#previewTable').DataTable({
                paging: true, // Menambahkan pagination
                searching: true, // Menambahkan pencarian
                info: true, // Menampilkan informasi tabel
                ordering: true, // Menambahkan sorting
                order: [
                    [0, 'asc']
                ], // Mengatur urutan default berdasarkan kolom pertama (NISN)
                columnDefs: [{
                    targets: 4, // Menargetkan kolom "Kesalahan"
                    visible: true // Menyembunyikan kolom kesalahan jika diperlukan
                }]
            });

            // Event saat file diunggah
            $('#file').on('change', function() {
                const file = this.files[0];
                if (file && (file.name.endsWith('.xls') || file.name.endsWith('.xlsx'))) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const workbook = XLSX.read(e.target.result, {
                            type: 'binary'
                        });
                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];
                        const data = XLSX.utils.sheet_to_json(sheet, {
                            header: 1
                        });

                        // Tampilkan nama file dan data
                        $('#uploadedFileName').text(`File: ${file.name}`);
                        $('#previewContainer').show();

                        // Kosongkan tabel preview sebelum menambah data baru
                        previewTable.clear();

                        let isValid = true; // Menandakan apakah data valid atau tidak
                        let errorCount = 0; // Menghitung jumlah kesalahan
                        let dataToImport = []; // Menampung data yang akan diimpor

                        // Tambahkan data ke tabel preview dan cek kesalahan
                        data.slice(1).forEach((row, index) => {
                            let errorMessage = '';
                            // Validasi contoh: Pastikan kolom NISN, Nama Siswa, dan Kelas tidak kosong
                            if (!row[0] || !row[1] || !row[3]) {
                                errorMessage = 'Data tidak lengkap';
                                isValid = false;
                                errorCount++;
                            }

                            // Tambahkan baris ke tabel dengan kolom kesalahan
                            previewTable.row.add([row[0], row[1], row[2], row[3],
                                errorMessage
                            ]);

                            // Simpan data untuk import jika tidak ada kesalahan
                            if (errorMessage === '') {
                                dataToImport.push({
                                    nisn: row[0],
                                    nama_siswa: row[1],
                                    email: row[2],
                                    kelas: row[3]
                                });
                            }
                        });

                        // Render ulang tabel preview
                        previewTable.draw();

                        // Sembunyikan atau tampilkan kolom Kesalahan berdasarkan jumlah kesalahan
                        if (errorCount === 0) {
                            previewTable.column(4).visible(
                                false); // Sembunyikan kolom Kesalahan jika tidak ada kesalahan
                        } else {
                            previewTable.column(4).visible(
                                true); // Tampilkan kolom Kesalahan jika ada kesalahan
                        }

                        // Tampilkan atau sembunyikan tombol Import berdasarkan validitas data
                        if (isValid) {
                            $('#importButton').show();
                        } else {
                            $('#importButton').hide();
                        }

                        // Menyimpan data yang valid untuk import setelah klik tombol Import
                        $('#btnImport').on('click', function() {
                            if (isValid) {
                                // Kirim data melalui Ajax
                                $.ajax({
                                    url: "{{ route('siswa.import') }}",
                                    method: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        data: dataToImport
                                    },
                                    success: function(response) {
                                        // Tampilkan pesan sukses atau lainnya setelah berhasil mengimpor
                                        alert('Data berhasil diimpor!');
                                    },
                                    error: function(xhr, status, error) {
                                        alert(
                                            'Terjadi kesalahan saat mengimpor data!'
                                        );
                                    }
                                });
                            }
                        });
                    };
                    reader.readAsBinaryString(file);
                } else {
                    alert('Harap unggah file Excel (.xls atau .xlsx)');
                }
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk tabel preview
            const previewTable = $('#previewTable').DataTable({
                paging: true, // Menambahkan pagination
                searching: true, // Menambahkan pencarian
                info: true, // Menampilkan informasi tabel
                ordering: true, // Menambahkan sorting
                order: [[0, 'asc']] // Mengatur urutan default berdasarkan kolom pertama (NISN)
            });

            // Event saat file diunggah
            $('#file').on('change', function() {
                const file = this.files[0];
                if (file && (file.name.endsWith('.xls') || file.name.endsWith('.xlsx'))) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const workbook = XLSX.read(e.target.result, { type: 'binary' });
                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];
                        const data = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                        // Tampilkan nama file dan data
                        $('#uploadedFileName').text(`File: ${file.name}`);
                        $('#previewContainer').show();

                        // Kosongkan tabel preview sebelum menambah data baru
                        previewTable.clear();

                        // Tambahkan data ke tabel preview
                        data.slice(1).forEach(row => {
                            previewTable.row.add([row[0], row[1], row[2], row[3]]);
                        });

                        // Render ulang tabel preview
                        previewTable.draw();
                    };
                    reader.readAsBinaryString(file);
                } else {
                    alert('Harap unggah file Excel (.xls atau .xlsx)');
                }
            });
        });
    </script> --}}


    <script>
        // Tentukan peran pengguna
        const userRole = "{{ auth()->user()->hasRole('Admin') ? 'Admin' : 'Siswa' }}";

        if (userRole === 'Admin') {
            // Inisialisasi DataTables untuk Admin
            $('#siswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siswa.getData') }}",
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'name',
                        name: 'user.name'
                    },
                    {
                        data: 'email',
                        name: 'user.email'
                    },
                    {
                        data: 'nisn',
                        name: 'nisn'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas.nama_kelas'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        } else if (userRole === 'Siswa') {
            // Tampilkan detail siswa untuk Siswa
            $.ajax({
                url: "{{ route('siswa.getDetail') }}", // Pastikan route ini sesuai
                method: "GET",
                success: function(response) {
                    if (response.success) {
                        // Populate the student details in the respective elements
                        $('#detailNama').text(response.data.name); // Use response.data if it's inside 'data'
                        $('#detailNISN').text(response.data.nisn);
                        $('#detailKelas').text(response.data.kelas);
                        $('#detailTotalPoin').text(response.total_poin); // Show total points

                        // Show the details section
                        $('#siswaDetail').show();
                    } else {
                        // Handle error case
                        alert('Data siswa tidak ditemukan');
                    }
                },
                error: function() {
                    alert('Gagal mengambil data siswa.');
                }
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            // $('#siswaTable').DataTable().destroy(); // Hancurkan DataTable yang ada sebelumnya

            // let siswaTable = $('#siswaTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "{{ route('siswa.getData') }}",
            //     columns: [
            //         {
            //             data: null, // Gunakan `null` untuk nomor urut
            //             orderable: false,
            //             searchable: false,
            //             render: function (data, type, row, meta) {
            //                 return meta.row + 1; // Hitung nomor urut berdasarkan index baris
            //             }
            //         },
            //         {
            //             data: 'name',
            //             name: 'user.name'
            //         },
            //         {
            //             data: 'email',
            //             name: 'user.email'
            //         },
            //         {
            //             data: 'nisn',
            //             name: 'nisn'
            //         },
            //         {
            //             data: 'kelas',
            //             name: 'kelas.nama_kelas'
            //         },
            //         {
            //             data: 'action',
            //             name: 'action',
            //             orderable: false,
            //             searchable: false
            //         }
            //     ]
            // });

            // Tambah Siswa Modal
            $('#btnAddSiswa').click(function() {
                $('#addSiswaModal').modal('show');
            });

            // Tambah Siswa Submit
            $('#addSiswaForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function() {
                        siswaTable.ajax.reload();
                        $('#addSiswaModal').modal('hide');
                    },
                    error: function() {
                        alert('Gagal menambah data siswa.');
                    }
                });
            });

            // Edit Siswa
            $(document).on('click', '.btn-edit', function() {
                let siswa = $(this).data();

                // Assign data to modal fields
                $('#edit_id').val(siswa.id);
                $('#edit_user_name').val(siswa.user_name); // Set the user name in the input field
                $('#edit_nisn').val(siswa.nisn);
                $('#edit_kelas_id').val(siswa.kelas_id);

                // Set form action
                $('#editSiswaForm').attr('action', "{{ url('siswa') }}/" + siswa.id);

                // Show the modal
                $('#editSiswaModal').modal('show');
            });


            // Edit Siswa Submit
            $('#editSiswaForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action'); // URL harus sesuai dengan route
                const method =
                    'POST'; // Gunakan POST, Laravel menangani PUT secara otomatis dengan @method('PUT')

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            alert(response.message); // Sukses
                            siswaTable.ajax.reload(); // Reload DataTables
                            $('#editSiswaModal').modal('hide'); // Tutup modal
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors ||
                        {}; // Ambil pesan error jika ada
                        alert('Gagal: ' + (xhr.responseJSON.message || 'Terjadi kesalahan.'));
                        console.error('Detail Error:', errors);
                    }
                });
            });

            // Hapus Siswa
            $(document).on('click', '.btn-delete', function() {
                var siswaId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus siswa ini?')) {
                    $.ajax({
                        url: '/siswa/' + siswaId,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.success);
                                $('#siswaTable').DataTable().ajax.reload();
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
