<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeranjangOrder extends Model
{
    use HasFactory;

    protected $table = 'keranjang_order';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'produk_tryout_id',
        'produk_bimbel_id',
        'customer_id',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function tryout(): BelongsTo
    {
        return $this->BelongsTo(ProdukTryout::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
