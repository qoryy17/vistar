<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'jawaban'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }
}
