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
        'total_nilai',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }
}
