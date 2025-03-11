<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('role-management.index', compact('users','roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('role-management.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('role-management.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('role-management.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('role-management.index')->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role-management.index')->with('success', 'Role berhasil dihapus!');
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('role-management.show', compact('role'));
    }
}
