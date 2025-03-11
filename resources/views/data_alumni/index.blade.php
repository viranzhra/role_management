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
            {{ __('Data Alumni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="alumniTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas Terakhir</th>
                                <th>Tahun Lulus</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        $('#alumniTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('alumni.data') }}", // Pastikan rute ini sesuai dengan route Anda
            columns: [
                { data: 'nama_siswa', name: 'nama_siswa' },
                { data: 'kelas_terakhir', name: 'kelas_terakhir' },
                { data: 'tahun_lulus', name: 'tahun_lulus' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
