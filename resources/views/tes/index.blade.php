<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tes Jurusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2>Soal Tes Jurusan {{ $siswa->jurusan->nama }}</h2>

                    @if ($soal->isEmpty())
                        <p>Tidak ada soal untuk jurusan ini.</p>
                    @else
                    <form action="{{ route('ujian.submit') }}" method="POST">
                        @csrf
                        @foreach($soal as $index => $s)
                            <div class="mb-3">
                                <p><strong>{{ $index + 1 }}. {{ $s->pertanyaan }}</strong></p>
                                <input type="hidden" name="jawaban[{{ $s->id }}]" value="">
                                <label><input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $s->pilihan_a }}"> {{ $s->pilihan_a }}</label><br>
                                <label><input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $s->pilihan_b }}"> {{ $s->pilihan_b }}</label><br>
                                <label><input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $s->pilihan_c }}"> {{ $s->pilihan_c }}</label><br>
                                <label><input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $s->pilihan_d }}"> {{ $s->pilihan_d }}</label><br>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
