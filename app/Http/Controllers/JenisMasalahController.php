<?php

namespace App\Http\Controllers;

use App\Models\JenisMasalah;
use Illuminate\Http\Request;

class JenisMasalahController extends Controller
{
    public function index()
    {
        $jenisMasalah = JenisMasalah::all(); // Ambil semua data jenis masalah
        return view('jenisMasalah.index', compact('jenisMasalah')); // Kirim data ke view
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_masalah' => 'required|string|max:255',
        ]);

        $jenisMasalah = JenisMasalah::create($request->all());
        
        return response()->json([
            'success' => true,
            'id' => $jenisMasalah->id,
            'nama_masalah' => $jenisMasalah->nama_masalah,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_masalah' => 'required|string|max:255',
        ]);

        $jenisMasalah = JenisMasalah::findOrFail($id);
        $jenisMasalah->update($request->all());

        return response()->json([
            'success' => true,
            'nama_masalah' => $jenisMasalah->nama_masalah,
        ]);
    }

    public function destroy($id)
    {
        $jenisMasalah = JenisMasalah::findOrFail($id);
        $jenisMasalah->delete();

        return response()->json(['success' => true]);
    }
}
