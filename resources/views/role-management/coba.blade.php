<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Role Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h2 class="mb-4">Manage Roles and Assign Roles to Users</h2>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Add Role Modal -->
                        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addRoleModal">Add Role</button>
                        
                        <!-- Manage Roles Table -->
                        <div class="card">
                            <div class="card-header">Manage Roles</div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Role Name</th>
                                            <th>Permissions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @foreach($role->permissions as $permission)
                                                        <span class="badge bg-secondary">{{ $permission->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">Edit</button>
                                                    
                                                    <!-- Delete Button -->
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRoleModal{{ $role->id }}">Delete</button>
                                                </td>
                                            </tr>

                                            <!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('role-management.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="role_name" name="role_name" value="{{ $role->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        @foreach($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" 
                                    @if(in_array($permission->id, $rolePermissions)) checked @endif>
                                <label class="form-check-label">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


                                            <!-- Delete Role Modal -->
                                            <div class="modal fade" id="deleteRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="deleteRoleModalLabel{{ $role->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteRoleModalLabel{{ $role->id }}">Delete Role</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('role-management.destroy', $role->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete the role <strong>{{ $role->name }}</strong>?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Assign Roles to Users -->
                        <div class="card mt-4">
                            <div class="card-header">Assign Roles to Users</div>
                            <div class="card-body">
                                <form action="{{ route('role-management.assign-role') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">User</label>
                                        <select name="user_id" id="user_id" class="form-control">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="role_id" class="form-label">Role</label>
                                        <select name="role_id" id="role_id" class="form-control">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Assign Role</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Role -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModalLabel">Tambah Role Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('role-management.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Nama Role</label>
                            <input type="text" class="form-control" id="roleName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            @foreach($permissions as $permission)
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
