<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'payment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ref_order_id',
        'status_order',
        'status_pesan',
        'nominal',
        'batas_pembayaran',
        'jenis_pembayaran',
        'biaya_admin',
        'status',
        'snap_token'
    ];

    // public $incrementing = false;

    public $timestamps = true;

    public function orderTryout(): BelongsTo
    {
        return $this->belongsTo(OrderTryout::class);
    }
}
