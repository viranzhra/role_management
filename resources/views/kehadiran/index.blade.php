<x-app-layout>
    <!-- Bootstrap dan DataTables -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi Harian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Informasi Waktu Absensi -->
                    {{-- <div class="alert alert-info mb-4">
                        Pengisian absensi hanya dapat dilakukan dari jam <strong>07:00</strong> sampai <strong>12:00</strong>.
                    </div> --}}

                    <!-- Form Filter -->
                    @if (!auth()->user()->hasRole('Siswa')) <!-- Jika bukan siswa -->
                    <form method="GET" action="{{ route('kehadiran.index') }}" class="mb-4">
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
                    @else
                        <!-- Jika siswa, kelas sudah otomatis berdasarkan data siswa yang login -->
                        <p class="text-muted">Kelas Anda: {{ $kelas->first()->nama_kelas }}</p>
                    @endif

                    <!-- Tampilkan tabel hanya jika kelas dipilih dan tanggal bukan libur -->
                    @if ($kelasId && !$isLibur)
                        <!-- Validasi waktu pengisian absensi -->
                        @php
                            $currentHour = \Carbon\Carbon::now()->hour;
                            // dari jam 7 - 12 siang
                            // $isAllowedTime = $currentHour >= 7 && $currentHour <= 12;

                            // Waktu absensi diperbolehkan 24 jam penuh
                            $isAllowedTime = true;
                        @endphp

                        @if ($isAllowedTime)
                        <!-- Form Absensi -->
                        <form action="{{ route('kehadiran.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control" required 
                                    value="{{ $tanggalAbsensi ?? \Carbon\Carbon::today()->toDateString() }}" 
                                    {{ isset($tanggalAbsensi) ? 'disabled' : '' }} />
                            </div>
                            <table id="getDataTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Hadir</th>
                                        <th>Alfa</th>
                                        <th>Sakit</th>
                                        <th>Izin</th>
                                        <th>Terlambat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $data)
                                    <tr>
                                        <td>{{ $data->user->name ?? 'Tidak ada nama' }}</td>
                                        @foreach (['Hadir', 'Alfa', 'Sakit', 'Izin', 'Terlambat'] as $status)
                                            <td class="px-4 py-2 text-center">
                                                <input 
                                                    type="radio" 
                                                    name="kehadiran[{{ $data->id }}][status]" 
                                                    value="{{ $status }}"
                                                    @if (old('kehadiran.' . $data->id . '.status', $data->kehadiran_status) == $status) 
                                                        checked 
                                                    @endif
                                                    required
                                                >
                                            </td>
                                        @endforeach
                                        <input type="hidden" name="kehadiran[{{ $data->id }}][siswa_id]" value="{{ $data->id }}">
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" id="saveButton" class="btn btn-primary">Simpan Absensi</button>
                            <button type="button" id="editButton" class="btn btn-warning" style="display: none;">Edit Absensi</button>
                        </form>

                        <script>
                            // Cek apakah absensi sudah ada
                            @if ($absensiSudahAda)
                                $('#saveButton').hide();
                                $('#editButton').show();
                            @endif

                            // Tombol edit mengaktifkan form kembali
                            $('#editButton').on('click', function () {
                                $('input[type="radio"]').prop('disabled', false);
                                $('#saveButton').show();
                                $('#editButton').hide();
                            });
                        </script>
                        @else
                            <div class="alert alert-danger">
                                Absensi hanya dapat dilakukan dari jam 07:00 sampai jam 12:00. Sekarang jam {{ \Carbon\Carbon::now()->format('H:i') }}.
                            </div>
                        @endif

                    @else
                        @if($isLibur)
                            <p style="color: red;">Hari ini adalah hari libur. Absensi tidak dapat dilakukan.</p>
                        @else
                            <!-- Jika kelas belum dipilih -->
                            <p class="text-muted">Silakan pilih kelas untuk menampilkan data siswa.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#getDataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/Indonesian.json"
                }
            });
        });
    </script>
</x-app-layout>
