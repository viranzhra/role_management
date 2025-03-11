<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    use HasFactory;

    protected $table = 'soal_ujian';
    protected $fillable = ['jurusan_id', 'pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban_benar'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
