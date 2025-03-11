<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\JenisMasalahController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\TesController;
use App\Http\Controllers\SiswaBaruController;
use App\Http\Controllers\JurusanController;

Route::middleware(['auth'])->group(function () {
    Route::get('/chat/index', [ChatbotController::class, 'index']);
    Route::post('/chat', [ChatbotController::class, 'chat']);
    Route::post('/chat', [ChatbotController::class, 'ask']);

    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::get('/user-management/getUserData', [UserManagementController::class, 'getUserData'])->name('user-management.getUserData');
    Route::get('/user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
    Route::post('/user-management/store', [UserManagementController::class, 'store'])->name('user-management.store');
    Route::get('/user-management/edit/{id}', [UserManagementController::class, 'edit'])->name('user-management.edit');
    Route::put('/user-management/update/{id}', [UserManagementController::class, 'update'])->name('user-management.update');
    Route::delete('/user-management/delete/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');

    // Route untuk RoleController
    Route::get('/role-management', [RoleController::class, 'index'])->name('role-management.index');
    // Route untuk mengambil data role (AJAX)
    // Route::get('/role-management/getData', [RoleController::class, 'getData'])->name('role-management.getData');
    Route::get('/role-management/create', [RoleController::class, 'create'])->name('role-management.create');
    Route::post('/role-management', [RoleController::class, 'store'])->name('role-management.store');
    Route::get('/role-management/{id}/edit', [RoleController::class, 'edit'])->name('role-management.edit');
    Route::put('/role-management/{id}', [RoleController::class, 'update'])->name('role-management.update');
    Route::delete('/role-management/{role}', [RoleController::class, 'destroy'])->name('role-management.destroy');
    // route untuk menampilkan role dan permissions
    Route::get('/role/{id}/permissions', [RoleController::class, 'getPermissions'])->name('role.permissions');

    // Route untuk SiswaController
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    // Route untuk mengambil data siswa (AJAX)
    Route::get('/siswa/getData', [SiswaController::class, 'getData'])->name('siswa.getData');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::post('/siswa/preview', [SiswaController::class, 'previewImport'])->name('siswa.preview');
    Route::get('/siswa/detail', [SiswaController::class, 'getDetail'])->name('siswa.getDetail');

    // Route untuk KelasController
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    // Route untuk mengambil data kelas (AJAX)
    Route::get('/kelas/getData', [KelasController::class, 'getData'])->name('kelas.getData');
    Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    Route::post('/kelas/naik-kelas', [KelasController::class, 'naikKelas'])->name('kelas.naikKelas');

    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/data', [AlumniController::class, 'getData'])->name('alumni.data');

    Route::get('/admin/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/admin/pengaduan/export', [PengaduanController::class, 'export'])->name('pengaduan.export');
    Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan/store', [PengaduanController::class, 'store'])->name('pengaduan.store');

    Route::get('jenisMasalah', [JenisMasalahController::class, 'index'])->name('jenisMasalah.index');
    Route::post('jenisMasalah', [JenisMasalahController::class, 'store'])->name('jenisMasalah.store');
    Route::put('jenisMasalah/{jenisMasalah}', [JenisMasalahController::class, 'update'])->name('jenisMasalah.update');
    Route::delete('jenisMasalah/{jenisMasalah}', [JenisMasalahController::class, 'destroy'])->name('jenisMasalah.destroy');

    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/kehadiran/getData', [KehadiranController::class, 'getData'])->name('kehadiran.getData');
    Route::post('/kehadiran/store', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::get('/kehadiran/download-sp/{id}', [KehadiranController::class, 'downloadSP'])->name('kehadiran.downloadSP');

    // Route::get('/tes', [TesController::class, 'tampilkanTes'])->name('tes.index');
    // Route::post('/tes/proses', [TesController::class, 'prosesTes'])->name('tes.proses');
    Route::get('/ujian', [TesController::class, 'index'])->name('ujian.index');
    Route::post('/ujian/submit', [TesController::class, 'submit'])->name('ujian.submit');


    Route::get('/siswabaru', [SiswaBaruController::class, 'index'])->name('siswa_baru.index');
    Route::post('/siswabaru/store', [SiswaBaruController::class, 'store'])->name('siswa_baru.store');

    Route::get('/jurusan/index', [JurusanController::class, 'index'])->name('jurusan.index');
    Route::get('/jurusan/create', [JurusanController::class, 'create'])->name('jurusan.create');
    Route::post('/jurusan/store', [JurusanController::class, 'store'])->name('jurusan.store');
    Route::get('/jurusan/edit/{id}', [JurusanController::class, 'edit'])->name('jurusan.edit');
    Route::put('/jurusan/update/{id}', [JurusanController::class, 'update'])->name('jurusan.update');
    Route::delete('/jurusan/delete/{id}', [JurusanController::class, 'destroy'])->name('jurusan.delete');

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
