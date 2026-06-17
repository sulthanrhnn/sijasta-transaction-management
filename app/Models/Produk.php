<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'harga',
        'stok',
        'gambar',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'decimal:2',
    ];

    public function stockLogs()
    {
        return $this->hasMany(KelolaStok::class);
    }
}
