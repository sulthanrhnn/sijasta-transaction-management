<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\View\View;

class MitraDashboardController extends Controller
{
    public function index(): View
    {
        $produk = Produk::where('stok', '>', 0)->orderBy('name')->paginate(12);

        return view('admin.dashboard.mitra', compact('produk'));
    }
}
