<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SoalUjianSeeder extends Seeder
{
    public function run()
    {
        $soal = [
            // Soal untuk Teknik Komputer & Jaringan
            [
                'jurusan_id' => 1,
                'pertanyaan' => 'Apa fungsi utama dari sebuah router dalam jaringan komputer?',
                'pilihan_a' => 'Menghubungkan jaringan yang berbeda',
                'pilihan_b' => 'Menyimpan data pengguna',
                'pilihan_c' => 'Menampilkan halaman web',
                'pilihan_d' => 'Menghapus virus dari komputer',
                'jawaban_benar' => 'Menghubungkan jaringan yang berbeda',
            ],
            [
                'jurusan_id' => 1,
                'pertanyaan' => 'Apa kepanjangan dari IP dalam IP Address?',
                'pilihan_a' => 'Internet Provider',
                'pilihan_b' => 'Internal Protocol',
                'pilihan_c' => 'Internet Protocol',
                'pilihan_d' => 'Integrated Program',
                'jawaban_benar' => 'Internet Protocol',
            ],
            [
                'jurusan_id' => 1,
                'pertanyaan' => 'Perangkat yang digunakan untuk memperkuat sinyal jaringan WiFi disebut?',
                'pilihan_a' => 'Modem',
                'pilihan_b' => 'Switch',
                'pilihan_c' => 'Repeater',
                'pilihan_d' => 'Router',
                'jawaban_benar' => 'Repeater',
            ],
            // Soal untuk Rekayasa Perangkat Lunak
            [
                'jurusan_id' => 2,
                'pertanyaan' => 'Apa fungsi utama dari bahasa pemrograman JavaScript?',
                'pilihan_a' => 'Membuat database',
                'pilihan_b' => 'Menambahkan interaktivitas pada website',
                'pilihan_c' => 'Mengatur tata letak halaman',
                'pilihan_d' => 'Menghubungkan server dengan client',
                'jawaban_benar' => 'Menambahkan interaktivitas pada website',
            ],
            [
                'jurusan_id' => 2,
                'pertanyaan' => 'Apa kepanjangan dari CSS?',
                'pilihan_a' => 'Cascading Style Sheet',
                'pilihan_b' => 'Computer Style System',
                'pilihan_c' => 'Creative Software Solution',
                'pilihan_d' => 'Cascading System Sheet',
                'jawaban_benar' => 'Cascading Style Sheet',
            ],
            [
                'jurusan_id' => 2,
                'pertanyaan' => 'Framework PHP yang banyak digunakan untuk pengembangan web adalah?',
                'pilihan_a' => 'Laravel',
                'pilihan_b' => 'Django',
                'pilihan_c' => 'Angular',
                'pilihan_d' => 'Vue.js',
                'jawaban_benar' => 'Laravel',
            ],
        ];

        foreach ($soal as $item) {
            DB::table('soal_ujian')->insert([
                'jurusan_id' => $item['jurusan_id'],
                'pertanyaan' => $item['pertanyaan'],
                'pilihan_a' => $item['pilihan_a'],
                'pilihan_b' => $item['pilihan_b'],
                'pilihan_c' => $item['pilihan_c'],
                'pilihan_d' => $item['pilihan_d'],
                'jawaban_benar' => $item['jawaban_benar'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
