<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Aduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('pengaduan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="jenis_masalah" class="form-label">Jenis Masalah</label>
                            <select name="jenis_masalah_id" id="jenis_masalah" class="form-select" required>
                                <option value="" disabled selected>Pilih jenis masalah</option>
                                @foreach ($jenisMasalah as $masalah)
                                    <option value="{{ $masalah->id }}">{{ $masalah->nama_masalah }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Masalah</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_anonim" id="is_anonim" value="1">
                            <label class="form-check-label" for="is_anonim">
                                Centang jika ingin sebagai anonim
                            </label>
                        </div>                        

                        {{-- <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_anonim" id="is_anonim"
                                value="1">
                            <label class="form-check-label" for="is_anonim">
                                Centang jika ingin sebagai anonim
                            </label>
                        </div> --}}

                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>

                    {{-- <form action="{{ route('pengaduan.store') }}" method="POST">
                        @csrf
                
                        <div class="mb-3">
                            <label for="sebagai" class="form-label">Sebagai</label>
                            <select name="sebagai" id="sebagai" class="form-select" required>
                                <option value="anonim">Anonim</option>
                                <option value="siswa" {{ old('sebagai', '') == 'siswa' ? 'selected' : '' }}>{{ Auth::user()->name }}</option>
                            </select>
                        </div>
                
                        <div class="mb-3" id="kelas-container">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" id="kelas" class="form-control" value="{{ $kelas }}" readonly>
                        </div>                        
                
                        <div class="mb-3">
                            <label for="aduan" class="form-label">Isi Aduan</label>
                            <textarea name="aduan" id="aduan" class="form-control" rows="5" placeholder="Tuliskan aduan Anda di sini..." required>{{ old('aduan') }}</textarea>
                        </div>
                
                        <button type="submit" class="btn btn-primary">Kirim Aduan</button>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            // Check if the success or error alert exists
            let successAlert = document.getElementById('success-alert');
            let errorAlert = document.getElementById('error-alert');

            // Set timeout to hide the alert after 5 seconds (5000 milliseconds)
            if (successAlert) {
                setTimeout(() => {
                    successAlert.classList.remove('show');
                    successAlert.classList.add('fade');
                    scrollToTop();
                }, 4000);
            }

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.classList.remove('show');
                    errorAlert.classList.add('fade');
                    scrollToTop();
                }, 4000);
            }

            // Function to scroll to the top of the page
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectSebagai = document.getElementById('sebagai');
            const kelasContainer = document.getElementById('kelas-container');
    
            selectSebagai.addEventListener('change', function () {
                if (this.value === 'anonim') {
                    kelasContainer.style.display = 'none';
                } else {
                    kelasContainer.style.display = 'block';
                }
            });

            // Initialize class visibility on page load
            if (selectSebagai.value === 'anonim') {
                kelasContainer.style.display = 'none';
            }
        });
    </script> --}}
</x-app-layout>
