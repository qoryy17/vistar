<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTryout extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'order_tryout';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'faktur_id',
        'customer_id',
        'nama',
        'produk_tryout_id',
        'payment_id',
        'status_order',
        'khusus'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tryout(): BelongsTo
    {
        return $this->BelongsTo(ProdukTryout::class, 'produk_tryout_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
