<?php

namespace App\Models\Sertikom;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeranjangOrderSertikom extends Model
{
    use HasFactory;

    protected $table = 'keranjang_order_sertikom';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'produk_pelatihan_seminar_id',
        'customer_id',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function sertikom(): BelongsTo
    {
        return $this->BelongsTo(ProdukPelatihanSeminarModel::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
