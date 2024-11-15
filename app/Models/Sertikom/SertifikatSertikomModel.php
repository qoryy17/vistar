<?php

namespace App\Models\Sertikom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SertifikatSertikomModel extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_sertikom';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nomor_indeks',
        'nomor_sertifikat',
        'produk_pelatihan_seminar_id',
        'peserta_sertikom_id'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function productSertikom(): BelongsTo
    {
        return $this->belongsTo(ProdukPelatihanSeminarModel::class);
    }

    public function participantSertikom(): BelongsTo
    {
        return $this->belongsTo(PesertaSertikomModel::class);
    }
}
