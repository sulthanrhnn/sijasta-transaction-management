<?php

namespace App\Http\Controllers;

use App\Models\KelolaStok;
use App\Models\Produk;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class AdminProdukController extends Controller
{
    public function index(): View
    {
        $produk = Produk::orderBy('stok')->paginate(20);

        if (Produk::where('stok', 0)->exists()) {
            Alert::error('Stok habis', 'Terdapat produk dengan stok kosong.');
        } elseif (Produk::where('stok', '>', 0)->where('stok', '<', 10)->exists()) {
            Alert::warning('Peringatan', 'Terdapat produk dengan stok kurang dari 10.');
        }

        return view('admin.layout.wrapper', [
            'produk' => $produk,
            'content' => 'admin.produk.index',
        ]);
    }

    public function create(): View
    {
        return view('admin.layout.wrapper', [
            'content' => 'admin.produk.create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProduct($request);
        $data['gambar'] = $this->storeImage($request);

        $produk = Produk::create($data);
        $this->recordStockChange($produk, 0, (float) $produk->stok, 'Penambahan produk baru');

        Alert::success('Berhasil', 'Produk berhasil ditambahkan.');
        return redirect()->route('produk.index');
    }

    public function edit(Produk $produk): View
    {
        return view('admin.layout.wrapper', [
            'produk' => $produk,
            'content' => 'admin.produk.edit',
        ]);
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $stockBefore = (float) $produk->stok;
        $data = $this->validatedProduct($request, $produk);

        if ($request->hasFile('gambar')) {
            $this->deleteImage($produk->gambar);
            $data['gambar'] = $this->storeImage($request);
        }

        $produk->update($data);
        $stockAfter = (float) $produk->stok;

        if ($stockBefore !== $stockAfter) {
            $this->recordStockChange($produk, $stockBefore, $stockAfter, 'Penyesuaian stok');
        }

        Alert::success('Berhasil', 'Produk berhasil diperbarui.');
        return redirect()->route('produk.index');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $this->deleteImage($produk->gambar);
        $produk->delete();

        Alert::success('Berhasil', 'Produk berhasil dihapus.');
        return redirect()->route('produk.index');
    }

    public function showKelolaStok(): View
    {
        return view('admin.layout.wrapper', [
            'kelolaStoks' => KelolaStok::with(['produk', 'user'])->latest()->paginate(20),
            'content' => 'admin.produk.kelolastok',
        ]);
    }

    public function detail(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $transactions = Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->get(['items']);

        $aggregated = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items ?? [] as $item) {
                $key = (string) ($item['produk_id'] ?? $item['produk_name']);
                $aggregated[$key] ??= [
                    'produk_name' => $item['produk_name'] ?? 'Produk',
                    'total_qty' => 0,
                ];
                $aggregated[$key]['total_qty'] += (float) ($item['qty'] ?? 0);
            }
        }

        $produkTerjual = Collection::make($aggregated)
            ->sortByDesc('total_qty')
            ->map(fn (array $item) => (object) $item)
            ->values();

        return view('admin.layout.wrapper', [
            'content' => 'admin.produk.detail',
            'produkTerjual' => $produkTerjual,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function search(Request $request): View
    {
        $keyword = trim((string) $request->input('q'));
        $produk = Produk::where('stok', '>', 0)
            ->when($keyword !== '', fn ($query) => $query->where('name', 'like', "%{$keyword}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.dashboard.mitra', compact('produk', 'keyword'));
    }

    private function validatedProduct(Request $request, ?Produk $produk = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:produks,name'.($produk ? ','.$produk->id : '')],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'numeric', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('gambar')) {
            return null;
        }

        $directory = public_path('uploads/images');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file('gambar');
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $name);

        return 'uploads/images/'.$name;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && is_file(public_path($path))) {
            unlink(public_path($path));
        }
    }

    private function recordStockChange(Produk $produk, float $before, float $after, string $activity): void
    {
        KelolaStok::create([
            'produk_id' => $produk->id,
            'jumlah_stok_tambah' => $after - $before,
            'stok_sebelum' => $before,
            'stok_sesudah' => $after,
            'aktivitas' => $activity,
            'user_id' => Auth::id(),
        ]);
    }
}
