<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'user_id',
        'kasir_name',
        'total',
        'status',
        'bukti_pembayaran',
        'items',
        'log_status',
        'keterangan',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'items' => 'array',
        'log_status' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
