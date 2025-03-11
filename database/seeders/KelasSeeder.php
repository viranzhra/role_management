<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ambil semua jurusan dari database
        $jurusan = DB::table('jurusan')->pluck('id');

        // Daftar kelas yang tersedia
        $kelas = ['10', '11', '12', 'Lulus'];

        // Loop setiap jurusan dan buat kelas untuk masing-masing
        foreach ($jurusan as $jurusan_id) {
            foreach ($kelas as $kelasName) {
                Kelas::create([
                    'nama_kelas' => $kelasName,
                    'jurusan_id' => $jurusan_id, // Sesuai dengan ID jurusan
                ]);
            }
        }
    }
}
