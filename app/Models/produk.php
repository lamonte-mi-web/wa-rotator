<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    use HasFactory;
    protected $table = 'produks';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'name',
        'price',
        'benefits',
        'image',
    ];

    // Menyimpan benefits sebagai JSON
    protected $casts = [
        'benefits' => 'array',
    ];
}