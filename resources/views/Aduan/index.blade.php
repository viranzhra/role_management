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
            {{ __('Aduan Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- <table id="pengaduanTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Aduan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                    
                    <script>
                    $(function () {
                        $('#pengaduanTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('pengaduan.index') }}",
                            columns: [
                                { data: 'id', name: 'id' },
                                { data: 'siswa', name: 'siswa' },
                                { data: 'kelas', name: 'kelas' },
                                { data: 'aduan', name: 'aduan' },
                                { data: 'action', name: 'action', orderable: false, searchable: false },
                            ],
                        });
                    });
                    </script> --}}

                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jenis Masalah</th>
                                <th>Deskripsi</th>
                                <th>Siswa</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengaduan as $aduan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $aduan->jenisMasalah->nama_masalah }}</td>
                                <td>{{ $aduan->deskripsi }}</td>
                                <td>{{ $aduan->is_anonim ? 'Anonim' : $aduan->siswa->user->name }}</td>
                                <td>{{ $aduan->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
