<?php

namespace App\Models\Sertikom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahapanSertikomModel extends Model
{
    use HasFactory;

    protected $table = 'tahapan_sertikom';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'kode',
        'produk_pelatihan_seminar_id',
        'tahapan'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function productSertikom(): BelongsTo
    {
        return $this->belongsTo(ProdukPelatihanSeminarModel::class);
    }
}
