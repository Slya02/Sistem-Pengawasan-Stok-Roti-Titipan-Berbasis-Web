<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokRoti extends Model
{
    use HasFactory;

    protected $table = 'stok_rotis';

    protected $fillable = [
        'user_id',
        'nama_toko',
        'tanggal_pengantaran',

        // semua varian
        'coklat_masuk',
        'srikaya_masuk',
        'strawberry_masuk',
        'kacang_masuk',
        'coklat_kacang_masuk',
        'coklat_strawberry_masuk',
        'mocca_masuk',
        'kopi_masuk',
        'keju_masuk',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'nama_toko', 'name');
    }

    public function sisa()
    {
        return $this->hasOne(SisaRoti::class, 'stok_roti_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
