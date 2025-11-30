<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SisaRoti extends Model
{
    use HasFactory;

    protected $table = 'sisa_rotis';

    protected $fillable = [
        'user_id',
        'stok_roti_id',
        'nama_toko',
        'tanggal_pengambilan',

        // SISA
        'coklat_sisa',
        'srikaya_sisa',
        'strawberry_sisa',
        'kacang_sisa',
        'coklat_kacang_sisa',
        'coklat_strawberry_sisa',
        'mocca_sisa',
        'kopi_sisa',
        'keju_sisa',

        // TERJUAL
        'coklat_terjual',
        'srikaya_terjual',
        'strawberry_terjual',
        'kacang_terjual',
        'coklat_kacang_terjual',
        'coklat_strawberry_terjual',
        'mocca_terjual',
        'kopi_terjual',
        'keju_terjual',

        // TOTAL
        'total_bill',
    ];

    public function stokRoti()
    {
        return $this->belongsTo(StokRoti::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'nama_toko', 'name');
    }
}
