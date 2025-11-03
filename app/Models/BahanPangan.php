<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanPangan extends Model
{
    protected $fillable = [
        'komoditas',
        'tanggal',
        'harga',
        'kategori',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'pasar',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'integer',
    ];
}
