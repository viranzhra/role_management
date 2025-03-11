<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMasalah extends Model
{
    use HasFactory;

    protected $table = 'jenis_masalah';

    protected $fillable = ['nama_masalah'];

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
