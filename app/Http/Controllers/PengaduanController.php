<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\JenisMasalah;
use App\Models\Pengaduan;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengaduanExport;

class PengaduanController extends Controller
{
    public function create()
    {
        $jenisMasalah = JenisMasalah::all();
        return view('aduan.form', compact('jenisMasalah'));
    }

    public function store(Request $request)
    {
        // Validasi input yang diterima
        $request->validate([
            'jenis_masalah_id' => 'required|exists:jenis_masalah,id',  // Pastikan jenis masalah ada
            'deskripsi' => 'required|string',  // Deskripsi wajib diisi
            'is_anonim' => 'nullable|boolean',  // Pastikan is_anonim bisa bernilai null atau boolean
        ]);

        // Membuat instance Pengaduan baru
        $pengaduan = new Pengaduan();
        $pengaduan->jenis_masalah_id = $request->jenis_masalah_id;  // Menyimpan jenis masalah
        $pengaduan->deskripsi = $request->deskripsi;  // Menyimpan deskripsi masalah

        // Jika tidak ada nilai untuk 'is_anonim', set default ke 'false'
        $pengaduan->is_anonim = $request->has('is_anonim') ? $request->is_anonim : false;

        // Jika pengguna memilih bukan anonim, simpan siswa_id berdasarkan data siswa yang sedang login
        if ($pengaduan->is_anonim === false && Auth::user()->siswa) {
            $pengaduan->siswa_id = Auth::user()->siswa->id;  // Mengambil siswa_id dari data siswa yang login
        }

        // Simpan pengaduan
        $pengaduan->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pengaduan.create')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function index()
    {
        $pengaduan = Pengaduan::with(['siswa', 'jenisMasalah'])->latest()->get();
        return view('aduan.index', compact('pengaduan'));
    }

    // public function index(Request $request)
    // {
    //     if (!auth()->user()->can('view aduan')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     if ($request->ajax()) {
    //         $data = Pengaduan::with('siswa')->select('pengaduan.*');
    //         return DataTables::of($data)
    //             ->addColumn('siswa', function ($row) {
    //                 return $row->siswa ? $row->siswa->name : 'Anonim';
    //             })
    //             ->addColumn('action', function ($row) {
    //                 return '<a href="' . route('pengaduan.detail', $row->id) . '" class="btn btn-sm btn-info">Detail</a>';
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }

    //     return view('aduan.index');
    // }

    // public function export(Request $request)
    // {
    //     if (!auth()->user()->can('export aduan')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     $kelas = $request->kelas;

    //     $pengaduan = Pengaduan::query();
    //     if ($kelas && $kelas != 'all') {
    //         $pengaduan->where('kelas', $kelas);
    //     }

    //     $data = $pengaduan->get();
    //     $fileName = $kelas ? 'Pengaduan_Kelas_' . $kelas . '.xlsx' : 'Pengaduan_All.xlsx';

    //     return Excel::download(new PengaduanExport($data), $fileName);
    // }

    // public function create()
    // {
    //     // Periksa hak akses
    //     if (!auth()->user()->can('create aduan')) {
    //         abort(403, 'Tidak diizinkan');
    //     }
    
    //     // Ambil data siswa yang sedang login
    //     $siswa = Siswa::with('kelas') // Memastikan kita mengambil relasi kelas
    //         ->where('user_id', auth()->user()->id) // Pastikan mengambil siswa berdasarkan user yang login
    //         ->first(); // Ambil hanya satu data siswa (karena user hanya bisa satu)
    
    //         // dd($siswa);
    //         // dd($siswa->kelas);
    //     // Jika data siswa ada, ambil nama kelas
    //     $kelas = $siswa ? $siswa->kelas->nama_kelas : null;
    
    //     // Kirim data kelas ke view
    //     return view('aduan.form', compact('kelas'));
    // }
    
    // public function store(Request $request)
    // {
    //     if (!auth()->user()->can('create aduan')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     $validated = $request->validate([
    //         'sebagai' => 'required|in:anonim,siswa',
    //         'aduan' => 'required|string',
    //     ]);

    //     $pengaduan = new Pengaduan();
    //     $pengaduan->aduan = $validated['aduan'];

    //     if ($validated['sebagai'] === 'siswa') {
    //         $pengaduan->siswa_id = Auth::id();
    //         $pengaduan->kelas = Auth::user()->kelas->nama_kelas; // asumsikan siswa punya atribut kelas
    //     } else {
    //         $pengaduan->kelas = 'Anonim';
    //     }

    //     $pengaduan->save();

    //     return redirect()->back()->with('success', 'Aduan berhasil dikirim.');
    // }
}
