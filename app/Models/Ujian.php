<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'order_tryout_id',
        'waktu_mulai',
        'waktu_berakhir',
        'durasi_ujian',
        'sisa_waktu',
        'soal_terjawab',
        'soal_belum_terjawab',
        'status_ujian'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderTryout::class);
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(ProgressUjian::class);
    }
}
