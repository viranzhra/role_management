<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        DB::table('jurusan')->insert([
            ['id' => 1, 'nama_jurusan' => 'Teknik Komputer dan Jaringan'],
            ['id' => 2, 'nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            // Tambahkan jurusan lainnya jika perlu
        ]);
    }
}
