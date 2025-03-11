<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Memanggil seeder untuk role dan permission
        $this->call(JurusanSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(KelasSeeder::class);
        $this->call(SiswaSeeder::class);

        // Membuat akun admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'), // Password yang di-hash
        ]);
        // Memberikan role Admin ke akun admin
        $admin->assignRole('Admin');

        // Membuat 3 akun siswa secara manual
        // $siswaRole = Role::where('name', 'Siswa')->first();

        // User::create([
        //     'name' => 'Siswa 1',
        //     'email' => 'siswa1@gmail.com',
        //     'password' => bcrypt('siswa123'), // Password yang di-hash
        // ])->assignRole($siswaRole);
    }
}
