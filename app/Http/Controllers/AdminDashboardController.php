<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Mitra;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'mitra') {
            // Ambil produk yang stoknya > 0 untuk mitra
            $produk = Produk::where('stok', '>', 0)->get();
            $content = 'admin.dashboard.mitra';

            return view('admin.dashboard.mitra', compact('content', 'produk'));
        }

        // Untuk admin / role lain
        $totalPendapatanTransaksi = Transaksi::where('status', 'selesai')->sum('total');
        $jumlahProduk = Produk::count();
        $jumlahMitra = Mitra::count();
        $totalTransaksiSelesai = Transaksi::where('status', 'selesai')->count();

        $content = 'admin.dashboard.index';

        return view('admin.layout.wrapper', compact(
            'totalPendapatanTransaksi',
            'jumlahProduk',
            'jumlahMitra',
            'totalTransaksiSelesai',
            'content'
        ));
    }
}
