<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressUjian extends Model
{
    use HasFactory;

    protected $table = 'progres_ujian';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ujian_id',
        'soal_ujian_id',
        'kode_soal',
        'jawaban',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(SoalUjian::class, 'soal_ujian_id');
    }
}
