<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Kehadiran;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = null;
        $kelas = Kelas::where('nama_kelas', '!=', 'Lulus')->get(); // Hanya kelas yang belum lulus // Ambil semua kelas untuk pemilihan kelas
        $tanggal = \Carbon\Carbon::today()->toDateString(); // Get today's date
        $isLibur = $this->isLibur($tanggal); // Cek apakah hari ini libur

        // Cek jika user yang login adalah siswa
        if (auth()->user()->hasRole('Siswa')) {
            // Ambil kelas dari siswa yang login
            $siswa = Siswa::where('user_id', auth()->id())->first();
            $kelasId = $siswa->kelas_id; // Kelas siswa yang login
            $kelas = Kelas::where('id', $kelasId)->get(); // Ambil data kelas tersebut

            // Ambil data siswa dalam kelas tersebut
            $siswa = Siswa::where('kelas_id', $kelasId)->get();
        } else {
            // Jika admin, biarkan admin memilih kelas
            if ($request->has('kelas_id')) {
                // Jika ada parameter kelas_id dalam request, ambil kelas sesuai dengan kelas_id yang dipilih
                $kelasId = $request->kelas_id;
                $siswa = Siswa::where('kelas_id', $kelasId)->get();
            } else {
                // Ambil semua siswa jika kelas_id tidak dipilih
                $siswa = Siswa::all();
            }
        }

        // Lakukan pengecekan dan kirim data ke view
        $absensiSudahAda = Kehadiran::where('tanggal', \Carbon\Carbon::today()->toDateString())->exists();

        return view('kehadiran.index', compact('kelas', 'kelasId', 'siswa', 'absensiSudahAda', 'isLibur'));
    }

    public function isLibur($tanggal)
    {
        $holidays = [
            '2025-01-01', // New Year's Day
            '2025-12-25', // Christmas Day
            // Add more holidays here
        ];

        return in_array($tanggal, $holidays);
    }

    public function getData(Request $request)
    {
        if (!auth()->user()->can('view kehadiran')) {
            abort(403, 'Tidak diizinkan');
        }

        $kelas = Kelas::where('nama_kelas', '!=', 'Lulus')->get();
        $kelasId = $request->get('kelas_id');

        $kehadiran = Kehadiran::with(['siswa.user', 'siswa.kelas']) // Pastikan relasi kelas ada
            ->when($kelasId, function ($query) use ($kelasId) {
                $query->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            })
            ->get();

        return view('kehadiran.getData', compact('kelas', 'kelasId', 'kehadiran'));
    }

    // Proses input absensi
    public function store(Request $request)
    {
        // Validasi data
        $data = $request->all();
        $rules = [
            'tanggal' => 'required|date',
            'kehadiran.*.siswa_id' => 'required|exists:siswa,id',
            'kehadiran.*.status' => 'required|in:Hadir,Alfa,Sakit,Izin,Terlambat',
        ];
        $request->validate($rules);

        // Proses simpan absensi dan hitung poin
        foreach ($data['kehadiran'] as $kehadiran) {
            // Cek apakah absensi untuk siswa pada tanggal tersebut sudah ada
            $absensi = Kehadiran::where('siswa_id', $kehadiran['siswa_id'])
                ->where('tanggal', $data['tanggal'])
                ->first();

            // Jika sudah ada, update absensi, jika belum simpan baru
            if ($absensi) {
                // Update data absensi yang sudah ada
                $absensi->update([
                    'status' => $kehadiran['status'],
                    'poin' => $this->hitPoin($kehadiran['status']),
                ]);
            } else {
                // Simpan data absensi baru
                Kehadiran::create([
                    'siswa_id' => $kehadiran['siswa_id'],
                    'tanggal' => $data['tanggal'],
                    'status' => $kehadiran['status'],
                    'poin' => $this->hitPoin($kehadiran['status']),
                ]);
            }

            // Update total poin siswa
            $siswa = Siswa::find($kehadiran['siswa_id']);
            $siswa->increment('total_poin', $this->hitPoin($kehadiran['status']));
        }

        return redirect()->back()->with('success', 'Data absensi berhasil disimpan.');
    }

    private function hitPoin($status)
    {
        // Menghitung poin berdasarkan status kehadiran
        return match ($status) {
            'Alfa' => 10,
            'Terlambat' => 5,
            default => 0,
        };
    }

    // Unduh Surat Pernyataan
    public function downloadSP($id)
    {
        $siswa = Siswa::findOrFail($id);

        // Cek apakah poin siswa melebihi batas
        $batasPoin = 50; // Contoh batas poin
        if ($siswa->total_poin < $batasPoin) {
            return redirect()->back()->with('error', 'Siswa belum mencapai batas poin untuk mendapatkan SP.');
        }

        // Buat file PDF Surat Pernyataan
        $pdf = PDF::loadView('surat_pernyataan', compact('siswa'));
        return $pdf->download("SP_{$siswa->nama}.pdf");
    }
}
