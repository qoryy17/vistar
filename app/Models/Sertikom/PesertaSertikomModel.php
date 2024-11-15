<?php

namespace App\Models\Sertikom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesertaSertikomModel extends Model
{
    use HasFactory;

    protected $table = 'peserta_sertikom';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'tahapan_sertikom_kode',
        'kode_peserta',
        'order_pelatihan_seminar_id',
        'nama',
        'kontak',
        'link_pretest',
        'link_posttest',
        'path_sertifikat_kehadiran',
        'path_sertifikat_pelatihan'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function orderSertikom(): BelongsTo
    {
        return $this->belongsTo(OrderPelatihanSeminarModel::class);
    }

    public function stepSertikom(): BelongsTo
    {
        return $this->belongsTo(TahapanSertikomModel::class);
    }
}
