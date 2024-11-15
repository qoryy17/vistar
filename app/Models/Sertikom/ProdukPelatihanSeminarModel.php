<?php

namespace App\Models\Sertikom;

use App\Models\OrderTryout;
use App\Models\KategoriProduk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukPelatihanSeminarModel extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'produk_pelatihan_seminar';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode',
        'produk',
        'harga',
        'deskripsi',
        'instruktur_id',
        'kategori_produk_id',
        'topik_keahlian_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'thumbnail',
        'publish',
        'link_zoom',
        'link_wa',
        'link_rekaman',
        'status',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(InstrukturModel::class, 'instruktur_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }

    public function expertise(): BelongsTo
    {
        return $this->belongsTo(TopikKeahlianModel::class, 'topik_keahlian_id');
    }

    // Call this method by conditioning with customer_id
    public function order(): HasOne
    {
        return $this->hasOne(OrderTryout::class, 'produk_tryout_id');
    }
}
