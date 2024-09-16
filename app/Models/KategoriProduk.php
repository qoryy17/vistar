<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'judul',
        'status',
        'aktif',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function produkTryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class, 'kategori_produk_id');
    }
}
