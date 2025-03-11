<?php

namespace App\Http\Controllers;

use App\Models\SiswaBaru;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SiswaBaruController extends Controller
{

    public function index()
    {
        $jurusan = Jurusan::all();
        return view('siswa_baru.index', compact('jurusan'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswa_baru,nisn',
            'nilai_tes' => 'nullable|integer',
            'jurusan_id' => 'nullable|exists:jurusan,id',
        ]);

        // Buat akun user
        $user = User::create([
            'name' => $request->nama,
            'email' => strtolower(str_replace(' ', '', $request->nama)) . '@example.com',
            'password' => Hash::make($request->nisn), // Password = NISN
        ]);

        // Ambil atau buat role "Siswa Baru"
        $siswaRole = Role::firstOrCreate(['name' => 'Siswa Baru']);
        
        // Berikan role "Siswa" ke user
        $user->assignRole($siswaRole);

        // Simpan siswa baru
        SiswaBaru::create([
            'user_id' => $user->id,
            'jurusan_id' => $request->jurusan_id,
            'nilai_tes' => $request->nilai_tes,
            'nisn' => $request->nisn,
        ]);

        return redirect()->route('siswa_baru.index')->with('success', 'Siswa Baru berhasil ditambahkan.');
    }
}
