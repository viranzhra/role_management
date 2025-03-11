<x-app-layout>
    <!-- Bootstrap dan DataTables -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form Filter -->
                    <form method="GET" action="{{ route('kehadiran.getData') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="kelas_id" class="form-label">Pilih Kelas</label>
                                <select id="kelas_id" name="kelas_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}" {{ $kelasId == $kls->id ? 'selected' : '' }}>
                                            {{ $kls->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Daftar Kehadiran -->
                    <table id="daftarKehadiranTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th> <!-- Kolom kelas ditambahkan -->
                                <th>Tanggal</th>
                                <th>Status Kehadiran</th>
                                <th>Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kehadiran as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->siswa->user->name }}</td>
                                    <td>{{ $data->siswa->kelas->nama_kelas }}</td> <!-- Menampilkan nama kelas -->
                                    <td>{{ $data->tanggal }}</td>
                                    <td>{{ $data->status }}</td>
                                    <td>{{ $data->poin }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#daftarKehadiranTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/Indonesian.json"
                }
            });
        });
    </script>
</x-app-layout>
