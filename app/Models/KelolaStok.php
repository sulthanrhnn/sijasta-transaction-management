<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaStok extends Model
{
    use HasFactory;

    protected $table = 'kelola_stoks';

    protected $fillable = [
        'produk_id',
        'jumlah_stok_tambah',
        'stok_sebelum',
        'stok_sesudah',
        'aktivitas',
        'user_id',
    ];

    protected $casts = [
        'jumlah_stok_tambah' => 'decimal:2',
        'stok_sebelum' => 'decimal:2',
        'stok_sesudah' => 'decimal:2',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
