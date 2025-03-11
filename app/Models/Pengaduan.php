<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = ['siswa_id', 'jenis_masalah_id', 'deskripsi', 'is_anonim'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisMasalah()
    {
        return $this->belongsTo(JenisMasalah::class);
    }
}
