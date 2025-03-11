<x-app-layout>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <style>
        /* button Lihat lebih banyak */
        .bg-selengkap {
            background-color: none;
            color: rgb(64, 64, 184);
        }

        .view-more-btn {
            cursor: pointer;
            /* Mengubah kursor menjadi pointer */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Animasi saat hover */
        }

        .view-more-btn:hover {
            background-color: none;
            color: blue;
        }

        .view-more-btn:focus {
            outline: 2px solid;
            /* Fokus dengan border */
            outline-offset: 2px;
            /* Jarak antara border fokus dan elemen */
        }
    </style>

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

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                id="successAlert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <script>
                            // Tunggu hingga dokumen selesai dimuat
                            document.addEventListener('DOMContentLoaded', function() {
                                // Sembunyikan alert success setelah 2 detik
                                const successAlert = document.getElementById('successAlert');
                                if (successAlert) {
                                    setTimeout(() => {
                                        successAlert.style.display = 'none';
                                    }, 4000);
                                }

                                // Sembunyikan alert error setelah 2 detik
                                const errorAlert = document.getElementById('errorAlert');
                                if (errorAlert) {
                                    setTimeout(() => {
                                        errorAlert.style.display = 'none';
                                    }, 4000);
                                }
                            });
                        </script>
                        @if (auth()->user()->can('view role') ||
                                auth()->user()->can('edit role') ||
                                auth()->user()->can('create role') ||
                                auth()->user()->can('delete role'))
                            <!-- Manage Roles Table -->
                            <div class="card">
                                <div class="card-header">Manage Roles</div>
                                <div class="card-body">
                                    <!-- Add Role Modal -->
                                    <button class="btn btn-primary mb-4" data-bs-toggle="modal"
                                        data-bs-target="#addRoleModal">Add
                                        Role</button>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Role Name</th>
                                                <th>Permissions</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $role)
                                                <tr>
                                                    <td>{{ $role->name }}</td>

                                                    {{-- Kode bagian data permissions --}}
                                                    <td>
                                                        @foreach ($role->permissions->take(4) as $permission)
                                                            <span class="badge bg-secondary" data-bs-toggle="modal"
                                                                data-bs-target="#permissionModal"
                                                                data-role-id="{{ $role->id }}">
                                                                {{ $permission->description }}
                                                            </span>
                                                        @endforeach

                                                        <!-- Jika ada lebih dari 3 permission, tampilkan tombol "Selengkapnya" -->
                                                        @if ($role->permissions->count() > 4)
                                                            <span class="badge bg-selengkap view-more-btn"
                                                                data-bs-toggle="modal"
                                                                data-role-id="{{ $role->id }}"
                                                                data-bs-target="#permissionModal">
                                                                [ Tampilkan lebih banyak ]
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <div class="modal fade" id="permissionModal" tabindex="-1"
                                                        aria-labelledby="permissionModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title fw-bold"
                                                                        id="permissionModalLabel">Daftar Permissions
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body"
                                                                    style="font-family: 'Poppins', sans-serif; font-size: 1.1rem;">
                                                                    <ul id="permissionsList"
                                                                        class="list-group list-group-flush"
                                                                        style="text-align: center; max-height: 400px; overflow-y: auto;">
                                                                        <!-- Daftar permissions akan diisi secara dinamis -->
                                                                    </ul>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    {{-- <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        $(document).on('click', '.view-more-btn', function() {
                                                            var roleId = $(this).data('role-id'); // Ambil ID role yang dipilih

                                                            // AJAX request untuk mendapatkan semua permissions yang terkait dengan role
                                                            $.ajax({
                                                                url: '/role/' + roleId + '/permissions', // Ganti dengan route Anda
                                                                method: 'GET',
                                                                success: function(response) {
                                                                    var rolePermissions = response
                                                                        .rolePermissions; // Permissions yang sudah dimiliki oleh role
                                                                    var allPermissions = response.allPermissions; // Semua permissions yang tersedia

                                                                    // Update modal dengan permissions
                                                                    var permissionsList = '';
                                                                    rolePermissions.forEach(function(permission) {
                                                                        permissionsList +=
                                                                            '<li class="list-group-item" style="color:#3c3c3c !important;font-size:16px">' +
                                                                            permission.description + '</li>';
                                                                    });

                                                                    allPermissions.forEach(function(permission) {
                                                                        if (!rolePermissions.some(p => p.id === permission.id)) {
                                                                            permissionsList +=
                                                                                '<li class="list-group-item" style="color:#3c3c3c !important;font-size:16px">' +
                                                                                permission.description + '</li>';
                                                                        }
                                                                    });

                                                                    $('#permissionsList').html(permissionsList); // Isi daftar ke dalam modal
                                                                    $('#permissionModal').modal('show'); // Tampilkan modal
                                                                },
                                                                error: function(xhr) {
                                                                    console.error('Gagal mengambil data permissions:', xhr);
                                                                    alert('Terjadi kesalahan saat mengambil data.');
                                                                }
                                                            });
                                                        });
                                                    </script>

                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#editRoleModal{{ $role->id }}">Edit</button>

                                                        <!-- Delete Button -->
                                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRoleModal{{ $role->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Delete Role Modal -->
                                                <div class="modal fade" id="deleteRoleModal{{ $role->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="deleteRoleModalLabel{{ $role->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deleteRoleModalLabel{{ $role->id }}">Delete
                                                                    Role
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form
                                                                action="{{ route('role-management.destroy', $role->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete the role
                                                                        <strong>{{ $role->name }}</strong>?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Delete</button>
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

                            <!-- Modal Add Role -->
                            <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog"
                                aria-labelledby="addRoleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('role-management.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addRoleModalLabel">Add Role</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="role-name">Role Name</label>
                                                    <input type="text" id="role-name" name="name"
                                                        class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Permissions</label>
                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                class="form-check-input">
                                                            <label
                                                                class="form-check-label">{{ $permission->description }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                {{-- <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button> --}}
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal Edit -->
                            @foreach ($roles as $role)
                                <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1"
                                    aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">
                                                    Edit
                                                    Role:
                                                    {{ $role->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('role-management.update', $role->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Role Name -->
                                                    <div class="form-group">
                                                        <label for="roleName{{ $role->id }}">Role Name</label>
                                                        <input type="text" name="name"
                                                            id="roleName{{ $role->id }}" class="form-control"
                                                            value="{{ $role->name }}" required>
                                                    </div>

                                                    <!-- Permissions -->
                                                    <div class="form-group">
                                                        <label>Permissions</label>
                                                        @foreach ($allPermissions as $permission)
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" name="permissions[]"
                                                                    value="{{ $permission->id }}"
                                                                    class="form-check-input"
                                                                    @if (in_array($permission->id, $role->permissions->pluck('id')->toArray())) checked @endif>
                                                                <label
                                                                    class="form-check-label">{{ $permission->description }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Update
                                                            Role</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <script>
                                // jQuery script to handle modal display
                                $(document).ready(function() {
                                    $('[data-bs-toggle="modal"]').on('click', function() {
                                        var modalId = $(this).attr('data-bs-target');
                                        $(modalId).modal('show');
                                    });
                                });
                            </script>
                        @endif

                        @if (auth()->user()->can('view user') ||
                                auth()->user()->can('edit user') ||
                                auth()->user()->can('create user') ||
                                auth()->user()->can('delete user'))
                            <!-- Manage Akun User -->
                            <div class="card mt-4">
                                <div class="card-header">Manage Users</div>
                                <div class="card-body">
                                    <!-- Tombol Tambah User -->
                                    <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">Tambah User</button>

                                    <table id="userTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Roles</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be filled by DataTable -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Modal Tambah User -->
                            <div class="modal fade" id="addUserModal" tabindex="-1"
                                aria-labelledby="addUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="addUserForm">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- User Form Fields -->
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="roles" class="form-label">Roles</label>
                                                    <div id="roles" class="form-check form-switch">
                                                        @foreach ($roles as $role)
                                                            <input class="form-check-input" type="checkbox"
                                                                id="role-{{ $role->id }}" name="roles[]"
                                                                value="{{ $role->id }}">
                                                            <label class="form-check-label"
                                                                for="role-{{ $role->id }}">{{ $role->name }}</label>
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit User -->
                            <div class="modal fade" id="editUserModal" tabindex="-1"
                                aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="editUserForm">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="editUserId" name="id">
                                                <div class="mb-3">
                                                    <label for="editName" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="editName"
                                                        name="name" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="roles" class="form-label">Roles</label>
                                                    <div id="roles" class="form-check form-switch">
                                                        @foreach ($roles as $role)
                                                            <input class="form-check-input" type="checkbox"
                                                                id="role-{{ $role->id }}" name="roles[]"
                                                                value="{{ $role->id }}">
                                                            <label class="form-check-label"
                                                                for="role-{{ $role->id }}">{{ $role->name }}</label>
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    // Initialize DataTables
                                    let userTable = $('#userTable').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        ajax: '{{ route('user-management.getUserData') }}', // Pastikan route ini mengembalikan data dalam format JSON
                                        columns: [{
                                                data: 'name',
                                                name: 'name'
                                            },
                                            {
                                                data: 'email',
                                                name: 'email'
                                            },
                                            {
                                                data: 'roles',
                                                name: 'roles'
                                            },
                                            {
                                                data: 'id',
                                                name: 'actions',
                                                orderable: false,
                                                searchable: false,
                                                render: function(data) {
                                                    return '<button class="btn btn-sm btn-primary edit-user" data-id="' +
                                                        data +
                                                        '" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>' +
                                                        '<button class="btn btn-sm btn-danger delete-user" data-id="' +
                                                        data + '">Delete</button>';
                                                }
                                            }
                                        ]
                                    });

                                    // Edit User
                                    $('#userTable').on('click', '.edit-user', function() {
                                        let id = $(this).data('id');

                                        $.get('{{ url('/user-management/edit') }}/' + id, function(data) {
                                            console.log(data); // Debugging: Pastikan data diterima dengan benar

                                            // Isi form dengan data yang diterima
                                            $('#editUserId').val(data.id);
                                            $('#editName').val(data.name);

                                            // Tandai checkbox yang sesuai berdasarkan role_ids yang diterima
                                            let roleIds = data.role_ids; // Ini adalah array ID role yang dimiliki user
                                            console.log(
                                                roleIds); // Debugging: Pastikan roleIds berisi array [1] atau ID lainnya

                                            // Centang checkbox yang sesuai dengan role ID
                                            roleIds.forEach(function(roleId) {
                                                // Debugging: Cek apakah checkbox ditemukan
                                                let checkbox = $('#role-' + roleId);
                                                console.log('Centang checkbox dengan ID role-' +
                                                    roleId); // Cek ID yang dicari

                                                // Pastikan checkbox ditemukan dan centang
                                                if (checkbox.length) {
                                                    checkbox.prop('checked', true);
                                                }
                                            });

                                            // Tampilkan modal
                                            $('#editUserModal').modal('show');
                                        });
                                    });
                                    // Update User
                                    $('#editUserForm').on('submit', function(e) {
                                        e.preventDefault();
                                        let id = $('#editUserId').val();
                                        $.ajax({
                                            url: '{{ url('/user-management/update') }}/' + id,
                                            type: 'PUT',
                                            data: $(this).serialize(),
                                            success: function(response) {
                                                $('#editUserModal').modal('hide');
                                                userTable.ajax.reload();
                                                alert('User berhasil diperbarui!');
                                            },
                                            error: function() {
                                                alert('Terjadi kesalahan!');
                                            }
                                        });
                                    });

                                    // Delete User
                                    $('#userTable').on('click', '.delete-user', function() {
                                        let id = $(this).data('id');
                                        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                                            $.ajax({
                                                url: '{{ url('/user-management/delete') }}/' + id,
                                                type: 'DELETE',
                                                success: function(response) {
                                                    userTable.ajax.reload();
                                                    alert('User berhasil dihapus!');
                                                },
                                                error: function() {
                                                    alert('Terjadi kesalahan!');
                                                }
                                            });
                                        }
                                    });
                                });
                            </script>
                        @endif
                    </div>
                </div>
</x-app-layout>
