<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Alumni;
use App\Models\Siswa; 
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas.
     */
    public function index()
    {
        // Mengecek izin akses
        if (!auth()->user()->can('view kelas')) {
            abort(403, 'Tidak diizinkan');
        }

        return view('kelas.index');
    }

    /**
     * Mendapatkan data kelas dalam format DataTables.
     */
    public function getData()
    {
        // Mengambil data kelas
        $kelas = Kelas::select('id', 'nama_kelas', 'jurusan_id');

        return DataTables::of($kelas)
            ->addColumn('action', function ($row) {
                $editButton = '';
                $deleteButton = '';

                // Cek hak akses untuk tombol edit
                if (auth()->user()->can('edit kelas')) {
                    $editButton = '<button class="btn btn-sm btn-primary btn-edit" 
                        data-id="' . $row->id . '" 
                        data-nama_kelas="' . $row->nama_kelas . '">
                    Edit
                    </button>';
                }

                // Cek hak akses untuk tombol hapus
                if (auth()->user()->can('delete kelas')) {
                    $deleteButton = '<button class="btn btn-sm btn-danger btn-delete" 
                                    data-id="' . $row->id . '">Hapus</button>';
                }

                // Gabungkan tombol Edit dan Hapus menggunakan flexbox
                return '<div class="d-flex justify-content-between">
                    ' . $editButton . ' ' . $deleteButton . '
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        // Mengecek izin akses
        if (!auth()->user()->can('create kelas')) {
            abort(403, 'Tidak diizinkan');
        }

        return view('kelas.create');
    }

    /**
     * Menyimpan kelas baru ke database.
     */
    public function store(Request $request)
    {
        // Mengecek izin akses
        if (!auth()->user()->can('create kelas')) {
            abort(403, 'Tidak diizinkan');
        }

        // Validasi data
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas',
        ]);

        // Menyimpan data kelas
        Kelas::create([
            'nama_kelas' => $validated['nama_kelas'],
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data kelas.
     */
    public function edit(Kelas $kelas)
    {
        // Mengecek izin akses
        if (!auth()->user()->can('edit kelas')) {
            abort(403, 'Tidak diizinkan');
        }

        return view('kelas.edit', compact('kelas'));
    }

    /**
     * Memperbarui data kelas yang ada.
     */
    // public function update(Request $request, Kelas $kelas)
    // {
    //     // Mengecek izin akses
    //     if (!auth()->user()->can('edit kelas')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     // Validasi data
    //     $validated = $request->validate([
    //         'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id,
    //     ]);

    //     // Memperbarui data kelas
    //     $kelas->update([
    //         'nama_kelas' => $validated['nama_kelas'],
    //     ]);

    //     // Redirect dengan pesan sukses
    //     return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        // Lakukan validasi
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        ]);
        
        $kelas = Kelas::find($id);
    
        if (!$kelas) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.'], 404);
        }
    
        // Update data siswa
        $kelas->nama_kelas = $validatedData['nama_kelas'];

        if ($kelas->save()) {
            return response()->json(['success' => true, 'message' => 'Data siswa berhasil diperbarui.']);
        }
    
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data siswa.'], 500);
    }

    /**
     * Menghapus kelas yang dipilih.
     */
    public function destroy(Kelas $kelas)
    {
        // Mengecek izin akses
        if (!auth()->user()->can('delete kelas')) {
            abort(403, 'Tidak diizinkan');
        }

        // Menghapus kelas
        $kelas->delete();

        return response()->json(['success' => 'Kelas berhasil dihapus.']);
    }



    public function naikKelas()
    {
        // Ambil semua kelas yang ada
        $kelas = Kelas::orderBy('nama_kelas')->get();
    
        // Ambil semua siswa
        $siswa = Siswa::all();
    
        foreach ($siswa as $s) {
            // Cari kelas siswa saat ini
            $currentKelas = $kelas->firstWhere('id', $s->kelas_id);
    
            if ($currentKelas) {
                // Jika siswa berada di kelas 12, anggap mereka sudah lulus
                if ((int)$currentKelas->nama_kelas === 12) {
                    // Pindahkan ke alumni
                    Alumni::create([
                        'nama' => $s->nama,
                        'kelas_terakhir' => 'Lulus',
                        'tahun_lulus' => now()->year,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
    
                    // Hapus siswa dari tabel siswa
                    $s->delete();
                } else {
                    // Cari kelas berikutnya
                    $nextKelas = $kelas->firstWhere('nama_kelas', (string)((int)$currentKelas->nama_kelas + 1));
    
                    if ($nextKelas) {
                        // Update kelas siswa ke kelas berikutnya
                        $s->kelas_id = $nextKelas->id;
                        $s->save();
                    }
                }
            }
        }
    
        return response()->json(['success' => true, 'message' => 'Proses kenaikan kelas berhasil.']);
    }
    
}
