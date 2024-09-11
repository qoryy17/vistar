<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiSoal extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi_soal';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'alias',
        'judul',
        'passing_grade',
        'ordering',
        'berbobot',
        'aktif',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function soal(): HasMany
    {
        return $this->hasMany(SoalUjian::class, 'klasifikasi_soal_id');
    }
}
