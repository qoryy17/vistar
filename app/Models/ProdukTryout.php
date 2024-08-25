<?php

namespace App\Models;

use App\Http\Controllers\Panel\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'thumbnail'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function user(): HasMany
    {
        return $this->hasMany(Users::class);
    }
}
