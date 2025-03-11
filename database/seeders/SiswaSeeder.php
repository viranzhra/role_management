<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class SiswaSeeder extends Seeder
{
    /**
     * Menjalankan seeder untuk mengisi data.
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Mendapatkan pengguna dengan id 2
        $user = User::find(2);

        // Mendapatkan semua kelas (tabel kelas)
        $kelas = Kelas::all();
        
        // Mendapatkan semua jurusan (tabel jurusan)
        $jurusan = Jurusan::all();

        // Pastikan ada data kelas terlebih dahulu
        if ($kelas->isEmpty()) {
            $kelas = collect([Kelas::create(['nama_kelas' => '10', 'jurusan_id' => 1])]); // Default kelas dengan jurusan_id = 1
        }

        // Pastikan ada data jurusan terlebih dahulu
        if ($jurusan->isEmpty()) {
            $jurusan = collect([Jurusan::create(['nama_jurusan' => 'Teknik Komputer dan Jaringan'])]);
        }

        if ($user) { // Jika pengguna dengan ID 2 ada
            // Jika pengguna belum memiliki role Siswa, beri role tersebut
            if (!$user->hasRole('Siswa')) {
                $user->assignRole('Siswa');
            }

            // Pilih kelas secara acak
            $selectedKelas = $kelas->random();
            
            // Ambil jurusan sesuai kelas yang dipilih
            $selectedJurusan = $jurusan->where('id', $selectedKelas->jurusan_id)->first();

            // Membuat siswa untuk pengguna dengan ID 2
            Siswa::create([
                'user_id' => $user->id,  // Menetapkan user_id dari model User
                'nisn' => $faker->unique()->numerify('############'),  // Membuat NISN yang unik
                'kelas_id' => $selectedKelas->id,  // Menetapkan kelas_id secara acak dari kelas yang tersedia
                'jurusan_id' => $selectedJurusan ? $selectedJurusan->id : null, // Tetapkan jurusan_id sesuai kelas
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
