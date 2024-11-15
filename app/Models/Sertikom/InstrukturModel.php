<?php

namespace App\Models\Sertikom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstrukturModel extends Model
{
    use HasFactory;

    protected $table = 'instruktur';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'instruktur',
        'keahlian',
        'deskripsi',
        'publish'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function productSertikom(): BelongsTo
    {
        return $this->belongsTo(ProdukPelatihanSeminarModel::class, 'instruktur_id');
    }
}
