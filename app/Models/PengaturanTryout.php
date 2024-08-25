<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengaturanTryout extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_tryout';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'harga',
        'harga_promo',
        'durasi',
        'nilai_keluar',
        'grafik_evaluasi',
        'review_pembahasan',
        'ulang_ujian',
        'masa_aktif'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class);
    }
}
