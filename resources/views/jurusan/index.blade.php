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
                        <h1>Daftar Jurusan</h1>
                        <a href="{{ route('jurusan.create') }}" class="btn btn-primary">Tambah Jurusan</a>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jurusan</th>
                                    <th>Nilai Minimum</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jurusan as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->nama_jurusan }}</td>
                                    <td>{{ $data->nilai_minimum ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('jurusan.edit', $data->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('jurusan.delete', $data->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus jurusan ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>