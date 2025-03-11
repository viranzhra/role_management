<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view role')) {
            abort(403, 'Tidak diizinkan');
        }

        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::all();
        $allPermissions = Permission::all(); // Semua permissions

        // Menyiapkan array untuk permissions yang diberikan ke setiap role
        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('id')->toArray();
        }

        return view('role-management.index', compact('roles', 'permissions', 'users', 'rolePermissions', 'allPermissions'));
    }


    // Menyimpan role baru
    public function store(Request $request)
    {
        if (!auth()->user()->can('create role')) {
            abort(403, 'Tidak diizinkan');
        }

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->permissions) {
            // Pastikan permissions yang dipilih adalah nama permission, bukan ID
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->givePermissionTo($permissions);
        }

        return redirect()->route('role-management.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit role')) {
            abort(403, 'Tidak diizinkan');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        // Mengambil permissions yang sudah diberikan ke role
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions, // Menambahkan permissions yang sudah dipilih
        ]);
    }

    // Update role
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array',
        ]);

        $role = Role::findOrFail($id);

        $role->update(['name' => $request->name]);

        // Pastikan permissions yang dipilih adalah nama permission, bukan ID
        if ($request->permissions) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('role-management.index')->with('success', 'Role updated successfully');
    }

    // Hapus role
    public function destroy($id)
    {
        if (!auth()->user()->can('delete role')) {
            abort(403, 'Tidak diizinkan');
        }

        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('role-management.index')->with('success', 'Role deleted successfully');
    }

    public function getPermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id); // Ambil role beserta permissions-nya
        $allPermissions = Permission::all(); // Semua permissions

        return response()->json([
            'rolePermissions' => $role->permissions, // Permissions yang dimiliki role
            'allPermissions' => $allPermissions,    // Semua permissions
        ]);
    }

//     public function getUserData(Request $request)
//     {
//         // Mengambil data pengguna dengan hubungan roles
//         $users = User::with('roles')->get();

//         // Menyediakan DataTables response
//         return DataTables::of($users)
//             ->addColumn('roles', function ($user) {
//                 // Menampilkan nama role yang dimiliki oleh user
//                 return $user->roles->pluck('name')->implode(', ');
//             })
//             ->addColumn('actions', function ($user) {
//                 return '
//                     <button class="btn btn-warning editUserBtn" data-user-id="' . $user->id . '">Edit</button>
//                     <button class="btn btn-danger deleteUserBtn" data-user-id="' . $user->id . '">Delete</button>
//                 ';
//             })
//             ->addColumn('password', function ($user) {
//                 // Menampilkan password yang dihash (tidak disarankan untuk menampilkan password asli)
//                 return '*****'; // Menyembunyikan password asli demi keamanan
//             })
//             ->rawColumns(['actions'])
//             ->make(true);
//     }
    
//     // Mengelola data pengguna
// public function storeUser(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|min:8',
//         'roles' => 'required|array',
//         'roles.*' => 'exists:roles,id',
//     ]);

//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => bcrypt($request->password),
//     ]);

//     $user->syncRoles($request->roles); // Menyinkronkan roles

//     return response()->json(['message' => 'User created successfully.'], 200);
// }

// // Update data pengguna
// public function updateUser(Request $request, $userId)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255',
//         'password' => 'nullable|string|min:8',
//         'roles' => 'required|array',
//         'roles.*' => 'exists:roles,id',
//     ]);

//     $user = User::findOrFail($userId);
//     $user->update([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => $request->password ? bcrypt($request->password) : $user->password,
//     ]);

//     $user->syncRoles($request->roles); // Menyinkronkan roles

//     return response()->json(['message' => 'User updated successfully.'], 200);
// }

// // Mengambil data pengguna untuk edit
// public function getUser($userId)
// {
//     $user = User::with('roles')->findOrFail($userId);
//     return response()->json([
//         'user' => $user,
//         'roles' => Role::all(),
//     ]);
// }

// // Menghapus pengguna
// public function deleteUser($userId)
// {
//     $user = User::findOrFail($userId);
//     $user->delete(); // Menghapus pengguna

//     return response()->json(['message' => 'User deleted successfully.'], 200);
// }

}
