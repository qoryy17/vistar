<?php

namespace App\Models;

use App\Http\Controllers\Panel\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'kontak',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'pendidikan',
        'jurusan',
        'foto',
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class);
    }
}
