<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralCustomer extends Model
{
    use HasFactory;

    protected $table = 'bank_referral';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'kode_referral',
        'customer_id',
        'produk_tryout_id'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class);
    }
}
