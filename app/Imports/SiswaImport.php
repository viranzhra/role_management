<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function model(array $row)
    {
        return new Siswa([
            'nisn' => $row['nisn'],            // Pastikan kolom sesuai dengan nama header di file Excel
            'name' => $row['name'],
            'email' => $row['email'],
            'kelas' => $row['kelas'],
        ]);
    }
    
    public function collection(Collection $rows)
    {
        $errors = [];

        // Validasi seluruh data sebelum memasukkan ke database
        foreach ($rows as $key => $row) {
            $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();

            if (!$kelas) {
                $errors[] = 'Kelas tidak ditemukan pada baris ' . ($key + 2) . ': ' . $row['kelas'];
            }
        }

        // Jika ada kesalahan, hentikan proses dan kirim pesan error
        if (!empty($errors)) {
            throw ValidationException::withMessages(['error' => $errors]);
        }

        // Proses data jika semua validasi berhasil
        foreach ($rows as $row) {
            // Buat atau ambil pengguna berdasarkan email
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                ['name' => $row['name'], 'password' => bcrypt('siswa123')]
            );

            // Simpan data siswa
            Siswa::create([
                'user_id' => $user->id,
                'nisn' => $row['nisn'],
                'kelas_id' => Kelas::where('nama_kelas', $row['kelas'])->first()->id,
            ]);
        }
    }
}
