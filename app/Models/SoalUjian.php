<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalUjian extends Model
{
    use HasFactory;

    protected $table = 'soal_ujian';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'soal',
        'gambar',
        'kode_soal',
        'jawaban_a',
        'jawaban_b',
        'jawaban_c',
        'jawaban_d',
        'jawaban_e',
        'poin_a',
        'poin_b',
        'poin_c',
        'poin_d',
        'poin_e',
        'kunci_jawaban',
        'klasifikasi_soal_id',
        'review_pembahasan',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function klasifikasiSoal(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiSoal::class);
    }
}
