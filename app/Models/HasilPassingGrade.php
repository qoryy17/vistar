<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPassingGrade extends Model
{
    use HasFactory;
    protected $table = 'hasil_pg_klasifikasi_soal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'hasil_ujian_id',
        'judul',
        'alias',
        'passing_grade',
        'terjawab',
        'terlewati',
        'benar',
        'salah',
        'total_nilai',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function hasil_ujian(): BelongsTo
    {
        return $this->belongsTo(HasilUjian::class, 'hasil_ujian_id');
    }
}
