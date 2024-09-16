<?php

namespace App\Models;

use App\Http\Controllers\Panel\Users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProdukTryout extends Model
{
    use HasFactory;

    protected $table = 'produk_tryout';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nama_tryout',
        'keterangan',
        'kode_soal',
        'pengaturan_tryout_id',
        'user_id',
        'kategori_produk_id',
        'status',
        'thumbnail',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(PengaturanTryout::class, 'pengaturan_tryout_id');
    }

    // Call this method by conditioning with customer_id
    public function order(): HasOne
    {
        return $this->hasOne(OrderTryout::class, 'produk_tryout_id');
    }
}
