<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Yajra\DataTables\Facades\DataTables;

class AlumniController extends Controller
{
    public function index()
    {
        return view('data_alumni.index');
    }

    public function getData()
    {
        // Ambil data alumni yang sudah lulus dan relasi ke siswa
        $alumni = Alumni::with('siswa:id,nama') // Mengambil data siswa yang terhubung dengan alumni
            ->select('id', 'siswa_id', 'kelas_terakhir', 'tahun_lulus')
            ->get();

        return DataTables::of($alumni)
            ->addColumn('nama_siswa', function ($row) {
                return $row->siswa ? $row->siswa->nama : 'Tidak Ada';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Hapus</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}