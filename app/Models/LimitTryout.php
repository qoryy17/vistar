<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LimitTryout extends Model
{
    use HasFactory;

    protected $table = 'limit_tryout';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'customer_id',
        'produk_tryout_id',
        'bukti_share',
        'bukti_follow',
        'informasi',
        'alasan',
        'status_validasi',
        'validasi_oleh',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function tryout(): BelongsTo
    {
        return $this->BelongsTo(ProdukTryout::class, 'produk_tryout_id');
    }

    public function ujian(): HasMany
    {
        return $this->hasMany(Ujian::class, 'limit_tryout_id');
    }
}
