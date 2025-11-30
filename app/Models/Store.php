<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'owner_name',
        'address',
        'join_date',
        'sales_id',
        'latitude',
        'longitude',
        'photo',
    ];

    protected $casts = [
        'join_date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }
}
