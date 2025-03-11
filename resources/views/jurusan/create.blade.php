<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Isi konten --}}
                    <div class="container">
                        <h1>Tambah Jurusan</h1>
                        <form action="{{ route('jurusan.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                                <input type="text" name="nama_jurusan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nilai_minimum" class="form-label">Nilai Minimum (Opsional)</label>
                                <input type="number" name="nilai_minimum" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
