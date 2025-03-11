<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Data Permission beserta Deskripsi
        $permissions = [
            ['name' => 'view siswa', 'description' => 'Melihat data siswa'],
            ['name' => 'create siswa', 'description' => 'Menambah data siswa'],
            ['name' => 'edit siswa', 'description' => 'Mengubah data siswa'],
            ['name' => 'delete siswa', 'description' => 'Menghapus data siswa'],

            ['name' => 'view kelas', 'description' => 'Melihat data kelas'],
            ['name' => 'create kelas', 'description' => 'Menambah data kelas'],
            ['name' => 'edit kelas', 'description' => 'Mengubah data kelas'],
            ['name' => 'delete kelas', 'description' => 'Menghapus data kelas'],

            ['name' => 'export siswa', 'description' => 'Mengekspor data siswa'],
            ['name' => 'import siswa', 'description' => 'Mengimpor data siswa'],

            ['name' => 'view role', 'description' => 'Melihat data role'],
            ['name' => 'create role', 'description' => 'Menambah data role'],
            ['name' => 'edit role', 'description' => 'Mengubah data role'],
            ['name' => 'delete role', 'description' => 'Menghapus data role'],

            ['name' => 'view user', 'description' => 'Melihat data user'],
            ['name' => 'create user', 'description' => 'Menambah data user'],
            ['name' => 'edit user', 'description' => 'Mengubah data user'],
            ['name' => 'delete user', 'description' => 'Menghapus data user'],

            ['name' => 'view aduan', 'description' => 'Melihat data aduan'],
            ['name' => 'create aduan', 'description' => 'Menambah data aduan'],
            ['name' => 'export aduan', 'description' => 'Mengekspor data aduan'],
            
            ['name' => 'view kehadiran', 'description' => 'Melihat data kehadiran'],
            ['name' => 'create kehadiran', 'description' => 'Menambah data kehadiran'],
            ['name' => 'edit kehadiran', 'description' => 'Mengubah data kehadiran'],
            ['name' => 'delete kehadiran', 'description' => 'Menghapus data kehadiran'],
            ['name' => 'view kelas kehadiran', 'description' => 'Melihat data kelas terkait kehadiran'],
            ['name' => 'edit kelas kehadiran', 'description' => 'Mengubah data kelas terkait kehadiran'],
            ['name' => 'view siswa kehadiran', 'description' => 'Melihat data siswa terkait kehadiran'],

            ['name' => 'tes jurusan', 'description' => 'Menjawab soal tes pemilihan jurusan'],
        ];

        // Menambahkan permission dengan deskripsi
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], ['description' => $permission['description']]);
        }

        // Role Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(array_column($permissions, 'name'));

        // Role Siswa
        $siswaRole = Role::firstOrCreate(['name' => 'Siswa']);
        $siswaRole->givePermissionTo(['view siswa', 'view kelas', 'create aduan']);
    }
}
