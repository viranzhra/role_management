<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoalUjian;
use App\Models\SiswaBaru;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TesController extends Controller
{
    // Menampilkan soal secara otomatis berdasarkan jurusan
    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $siswa = Auth::user()->siswaBaru;

        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses tes.');
        }

        // Ambil ID jurusan dari siswa
        $jurusan_id = $siswa->jurusan_id;

        // Ambil 10 soal acak berdasarkan jurusan siswa
        $soal = SoalUjian::where('jurusan_id', $jurusan_id)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        if ($soal->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Soal tidak ditemukan untuk jurusan Anda.');
        }

        return view('tes.index', compact('soal', 'jurusan_id', 'siswa'));
    }

    // Mengecek jawaban dan menampilkan skor serta menentukan hasil seleksi
    public function submit(Request $request)
    {
        $soal = SoalUjian::whereIn('id', array_keys($request->jawaban))->get();
        $benar = 0;

        foreach ($soal as $s) {
            if ($request->jawaban[$s->id] === $s->jawaban_benar) {
                $benar++;
            }
        }

        $skor = ($benar / count($soal)) * 100;
        $siswa = Auth::user()->siswaBaru;
        $jurusan_id = $siswa->jurusan_id;

        if ($skor >= 70) {
            // Jika diterima di jurusan yang dipilih
            $status = "Diterima di jurusan yang dipilih";
            $this->masukkanKeSiswa($siswa, $jurusan_id);
        } elseif ($skor >= 30) {
            // Jika harus dipindahkan oleh pihak sekolah
            $status = "Perlu penempatan ulang oleh sekolah";
        } else {
            // Jika tidak diterima
            $status = "Tidak diterima di sekolah";
        }

        return view('tes.result', compact('skor', 'benar', 'status'));
    }

    // Fungsi untuk memasukkan siswa yang diterima ke tabel siswa
    private function masukkanKeSiswa($siswa, $jurusan_id)
    {
        // Ambil kelas 10 secara dinamis berdasarkan jurusan
        $kelas = Kelas::where('jurusan_id', $jurusan_id)->where('nama_kelas', 10)->first();

        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Kelas tidak ditemukan.');
        }

        DB::table('siswa')->insert([
            'user_id' => $siswa->user_id,
            'nisn' => $siswa->nisn,
            'kelas_id' => $kelas->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Hapus dari tabel siswa_baru karena sudah resmi menjadi siswa
        $siswa->delete();
    }
}
