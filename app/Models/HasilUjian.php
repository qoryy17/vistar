<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'keterangan',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function testimoni(): HasOne
    {
        return $this->hasOne(Testimoni::class, 'hasil_ujian_id');
    }

    public function passing_grade(): HasMany
    {
        return $this->hasMany(HasilPassingGrade::class, 'hasil_ujian_id');
    }
}
