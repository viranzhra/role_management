<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = ['user_id', 'nisn', 'kelas_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    // Method to calculate total_poin
    public function totalPoin()
    {
        return $this->kehadiran()->sum('poin');
    }

    public function alumni()
    {
        return $this->hasMany(Alumni::class);
    }
}
