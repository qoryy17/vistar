<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ujian_id',
        'durasi_selesai',
        'benar',
        'salah',
        'terjawab',
        'tidak_terjawab',
        'total_nilai',
        'keterangan'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }
}
