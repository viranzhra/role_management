<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserManagementController extends Controller
{
    // Menampilkan halaman manajemen user
    public function index()
    {
        if (!auth()->user()->can('view user')) {
            abort(403, 'Tidak diizinkan');
        }

        return view('role-management.index'); // Tampilkan view utama
    }

    public function getUserData()
    {
        if (!auth()->user()->can('view user')) {
            abort(403, 'Tidak diizinkan');
        }

        // Ambil semua data user dengan roles
        $users = User::with('roles')->select('id', 'name', 'email')->get();

        return DataTables::of($users)
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->join(', '); // Gabungkan nama roles menjadi string
            })
            ->make(true);
    }

    // Menampilkan form tambah user
    public function create()
    {
        $roles = Role::all(); // Ambil semua roles
        return view('user-management.create', compact('roles'));
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        if (!auth()->user()->can('create user')) {
            abort(403, 'Tidak diizinkan');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
        ]);

        // Membuat user baru dan menyimpan password yang ter-enkripsi
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Menyimpan roles ke user menggunakan tabel role_user
        $user->roles()->sync($validated['roles']); // Sinkronkan roles di tabel pivot (role_user)

        return redirect()->route('role-management.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        // Menemukan user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Mengambil role IDs yang dimiliki user (menggunakan pluck)
        $roleIds = $user->roles->pluck('id')->toArray(); // Pastikan mengkonversi ke array
    
        // Mengembalikan data dalam bentuk JSON
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'role_ids' => $roleIds,  // Ini array ID role
        ]);
    }

    // Mengupdate data user
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit user')) {
            abort(403, 'Tidak diizinkan');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array',
        ]);

        // Mengupdate data user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        // Sinkronkan roles ke tabel pivot (role_user)
        $user->roles()->sync($validated['roles']); // Update roles user

        return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus user
    public function destroy($id)
    {
        if (!auth()->user()->can('delete user')) {
            abort(403, 'Tidak diizinkan');
        }

        $user = User::findOrFail($id);

        // Hapus roles yang terkait sebelum menghapus user
        $user->roles()->detach(); // Menghapus data di pivot table role_user

        $user->delete(); // Menghapus user dari tabel users

        return response()->json(['success' => 'User berhasil dihapus.']);
    }
}
