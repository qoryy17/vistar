<?php

namespace App\Models\Sertikom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopikKeahlianModel extends Model
{
    use HasFactory;

    protected $table = 'topik_keahlian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'topik',
        'deskripsi',
        'publish'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function productSertikom(): BelongsTo
    {
        return $this->belongsTo(ProdukPelatihanSeminarModel::class, 'topik_keahlian_id');
    }
}
