<?php

namespace App\Models\Sertikom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorProdukModel extends Model
{
    use HasFactory;

    protected $table = 'visitor_produk';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ref_produk_id',
        'nama_produk',
        'ip_address',
        'tanggal'
    ];

    public $incrementing = true;

    public $timestamps = true;
}
