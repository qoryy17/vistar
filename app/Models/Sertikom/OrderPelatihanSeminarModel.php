<?php

namespace App\Models\Sertikom;

use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPelatihanSeminarModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'order_pelatihan_seminar';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'faktur_id',
        'customer_id',
        'nama',
        'produk_pelatihan_seminar_id',
        'payment_id',
        'status_order',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function trainingSeminar(): BelongsTo
    {
        return $this->BelongsTo(ProdukPelatihanSeminarModel::class, 'produk_pelatihan_seminar_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
