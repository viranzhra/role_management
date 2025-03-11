<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Siswa Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4">Tambah Siswa Baru</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('siswa_baru.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nisn" class="form-label">NISN</label>
                                    <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" required>
                                    @error('nisn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="nilai_tes" class="form-label">Nilai Tes (Opsional)</label>
                                    <input type="number" class="form-control @error('nilai_tes') is-invalid @enderror" id="nilai_tes" name="nilai_tes" value="{{ old('nilai_tes') }}">
                                    @error('nilai_tes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="mb-3">
                                    <label for="jurusan_id" class="form-label">Jurusan</label>
                                    <select class="form-select @error('jurusan_id') is-invalid @enderror" id="jurusan_id" name="jurusan_id">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach($jurusan as $j)
                                            <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                                                {{ $j->nama_jurusan }}  {{-- Perbaiki dari $j->nama ke $j->nama_jurusan --}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                    @error('jurusan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('siswa_baru.index') }}" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
