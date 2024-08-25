<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanWeb extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_web';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nama_bisnis',
        'tagline',
        'perusahaan',
        'alamat',
        'email',
        'facebook',
        'instagram',
        'kontak',
        'logo',
        'meta_author',
        'meta_keyword',
        'meta_description'
    ];

    public $incrementing = false;

    public $timestamps = true;
}
