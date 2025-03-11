<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        return view('jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|unique:jurusan,nama_jurusan',
            'nilai_minimum' => 'nullable|integer',
        ]);

        Jurusan::create([
            'nama_jurusan' => $request->nama_jurusan,
            'nilai_minimum' => $request->nilai_minimum,
        ]);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|unique:jurusan,nama_jurusan,' . $id,
            'nilai_minimum' => 'nullable|integer',
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update([
            'nama_jurusan' => $request->nama_jurusan,
            'nilai_minimum' => $request->nilai_minimum,
        ]);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
