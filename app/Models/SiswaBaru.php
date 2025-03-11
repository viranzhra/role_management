<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiswaBaru extends Model
{
    use HasFactory;

    protected $table = 'siswa_baru';
    protected $fillable = ['user_id', 'nisn', 'jurusan_id', 'nilai_tes', 'status'];

    protected static function boot()
    {
        parent::boot();

        // Event ketika status berubah menjadi "diterima"
        static::updating(function ($siswaBaru) {
            if ($siswaBaru->status == 'diterima' && $siswaBaru->isDirty('status')) {
                DB::transaction(function () use ($siswaBaru) {
                    // Pindahkan ke tabel siswa
                    \App\Models\Siswa::create([
                        'user_id' => $siswaBaru->user_id,
                        'jurusan_id' => $siswaBaru->jurusan_id,
                        'nisn' => $siswaBaru->nisn,
                    ]);

                    // Update role di tabel users
                    $siswaBaru->user->update(['role' => 'Siswa']);
                });
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
