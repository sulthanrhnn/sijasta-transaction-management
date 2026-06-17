<?php

namespace Database\Seeders;

use App\Models\Mitra;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Demo Admin', 'password' => 'password', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'assistant@example.com'],
            ['name' => 'Demo Assistant', 'password' => 'password', 'role' => 'asisten']
        );

        User::updateOrCreate(
            ['email' => 'partner@example.com'],
            ['name' => 'Demo Partner', 'password' => 'password', 'role' => 'mitra']
        );

        Mitra::updateOrCreate(
            ['email' => 'partner@example.com'],
            [
                'name' => 'Demo Partner',
                'no_hp' => '081200000000',
                'alamat' => 'Pekanbaru, Indonesia',
            ]
        );

        foreach ([
            ['name' => 'Frozen Corn', 'harga' => 28000, 'stok' => 30],
            ['name' => 'Frozen Potato', 'harga' => 35000, 'stok' => 25],
            ['name' => 'Frozen Mixed Vegetables', 'harga' => 32000, 'stok' => 20],
        ] as $product) {
            Produk::updateOrCreate(['name' => $product['name']], $product + ['gambar' => null]);
        }
    }
}
