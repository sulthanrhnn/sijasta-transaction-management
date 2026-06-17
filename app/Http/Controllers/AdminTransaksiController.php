<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class AdminTransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $transactions = Transaksi::query()
            ->when($user->role === 'mitra', fn ($query) => $query->where('user_id', $user->id))
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        if ($user->role === 'mitra') {
            return view('admin.transaksi.indexmitra', [
                'transaksi' => $transactions,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }

        return view('admin.layout.wrapper', [
            'content' => 'admin.transaksi.index',
            'transaksi' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $user = $request->user();

        $transaction = Transaksi::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            [
                'kasir_name' => $user->name,
                'total' => 0,
                'items' => [],
                'log_status' => [[
                    'status_sebelumnya' => null,
                    'status_baru' => 'pending',
                    'oleh' => $user->name,
                    'waktu' => now()->toDateTimeString(),
                ]],
                'keterangan' => 'Transaksi baru dibuat.',
            ]
        );

        return redirect()->route('transaksi.edit', $transaction);
    }

    public function edit(Request $request, Transaksi $transaksi): View|RedirectResponse
    {
        $this->ensureAccessible($request, $transaksi);

        if ($transaksi->status !== 'pending') {
            Alert::info('Info', 'Transaksi yang sudah diproses tidak dapat diedit.');
            return redirect()->route('transaksi.index');
        }

        return view('admin.transaksi.create', [
            'transaksi' => $transaksi,
            'produk' => Produk::where('stok', '>', 0)->orderBy('name')->get(),
        ]);
    }

    public function addItem(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $this->ensureAccessible($request, $transaksi);
        abort_unless($transaksi->status === 'pending', 422, 'Transaksi tidak dapat diedit.');

        $validated = $request->validate([
            'produk_id' => ['required', 'exists:produks,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () use ($validated, $transaksi): void {
            $lockedTransaction = Transaksi::whereKey($transaksi->id)->lockForUpdate()->firstOrFail();
            $product = Produk::whereKey($validated['produk_id'])->lockForUpdate()->firstOrFail();
            $quantity = (float) $validated['qty'];

            if ($quantity > (float) $product->stok) {
                abort(422, 'Stok produk tidak mencukupi.');
            }

            $items = $lockedTransaction->items ?? [];
            $found = false;

            foreach ($items as $index => $item) {
                if ((int) $item['produk_id'] === $product->id) {
                    $items[$index]['qty'] = (float) $item['qty'] + $quantity;
                    $items[$index]['subtotal'] = $items[$index]['qty'] * (int) $item['harga'];
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $items[] = [
                    'produk_id' => $product->id,
                    'produk_name' => $product->name,
                    'qty' => $quantity,
                    'harga' => (int) $product->harga,
                    'subtotal' => $quantity * (int) $product->harga,
                ];
            }

            $lockedTransaction->update([
                'items' => array_values($items),
                'total' => collect($items)->sum('subtotal'),
            ]);

            $product->decrement('stok', $quantity);
        });

        Alert::success('Berhasil', 'Produk ditambahkan ke transaksi.');
        return redirect()->route('transaksi.edit', $transaksi);
    }

    public function removeItem(Request $request, Transaksi $transaksi, Produk $produk): RedirectResponse
    {
        $this->ensureAccessible($request, $transaksi);
        abort_unless($transaksi->status === 'pending', 422, 'Transaksi tidak dapat diedit.');

        DB::transaction(function () use ($transaksi, $produk): void {
            $lockedTransaction = Transaksi::whereKey($transaksi->id)->lockForUpdate()->firstOrFail();
            $lockedProduct = Produk::whereKey($produk->id)->lockForUpdate()->firstOrFail();
            $items = $lockedTransaction->items ?? [];
            $removed = null;

            foreach ($items as $index => $item) {
                if ((int) $item['produk_id'] === $lockedProduct->id) {
                    $removed = $item;
                    unset($items[$index]);
                    break;
                }
            }

            if (! $removed) {
                abort(404, 'Item transaksi tidak ditemukan.');
            }

            $lockedProduct->increment('stok', (float) $removed['qty']);
            $items = array_values($items);
            $lockedTransaction->update([
                'items' => $items,
                'total' => collect($items)->sum('subtotal'),
            ]);
        });

        Alert::success('Berhasil', 'Item dihapus dari transaksi.');
        return redirect()->route('transaksi.edit', $transaksi);
    }

    public function showPaymentPage(Request $request, Transaksi $transaksi): View|RedirectResponse
    {
        $this->ensureAccessible($request, $transaksi);

        if ($transaksi->status !== 'pending' || empty($transaksi->items)) {
            Alert::error('Gagal', 'Tambahkan produk sebelum melanjutkan pembayaran.');
            return redirect()->route('transaksi.edit', $transaksi);
        }

        return view('admin.transaksi.bayar', compact('transaksi'));
    }

    public function processPayment(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $this->ensureAccessible($request, $transaksi);
        abort_unless($transaksi->status === 'pending', 422, 'Transaksi tidak dapat dibayar.');

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($transaksi->bukti_pembayaran) {
            Storage::disk('local')->delete($transaksi->bukti_pembayaran);
        }

        $file = $request->file('payment_proof');
        $fileName = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('payments', $fileName, 'local');

        $transaksi->update([
            'bukti_pembayaran' => $path,
            'keterangan' => 'Bukti pembayaran telah diunggah.',
        ]);

        Alert::success('Berhasil', 'Bukti pembayaran berhasil diunggah.');
        return redirect()->route('transaksi.index');
    }


    public function paymentProof(Request $request, Transaksi $transaksi): Response
    {
        $this->ensureAccessible($request, $transaksi);
        abort_unless($transaksi->bukti_pembayaran, 404);
        abort_unless(Storage::disk('local')->exists($transaksi->bukti_pembayaran), 404);

        return Storage::disk('local')->response($transaksi->bukti_pembayaran);
    }

    public function updateStatus(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,diproses,selesai,ditolak'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $oldStatus = $transaksi->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            Alert::info('Info', 'Status tidak berubah.');
            return back();
        }

        if ($oldStatus === 'selesai') {
            Alert::error('Gagal', 'Transaksi selesai tidak dapat diubah.');
            return back();
        }

        DB::transaction(function () use ($transaksi, $oldStatus, $newStatus, $validated, $request): void {
            $lockedTransaction = Transaksi::whereKey($transaksi->id)->lockForUpdate()->firstOrFail();

            if ($newStatus === 'ditolak' && $oldStatus !== 'ditolak') {
                $this->restoreStock($lockedTransaction);
            }

            if ($oldStatus === 'ditolak' && $newStatus !== 'ditolak') {
                $this->reserveStock($lockedTransaction);
            }

            $logs = $lockedTransaction->log_status ?? [];
            $logs[] = [
                'status_sebelumnya' => $oldStatus,
                'status_baru' => $newStatus,
                'oleh' => $request->user()->name,
                'waktu' => now()->toDateTimeString(),
            ];

            $lockedTransaction->update([
                'status' => $newStatus,
                'keterangan' => $validated['keterangan'] ?? null,
                'log_status' => $logs,
            ]);
        });

        Alert::success('Berhasil', 'Status transaksi berhasil diperbarui.');
        return back();
    }

    public function cetakStruk(Request $request, Transaksi $transaksi): Response
    {
        $this->ensureAccessible($request, $transaksi);
        abort_unless($transaksi->status === 'selesai', 403);

        return Pdf::loadView('admin.transaksi.struk', [
            'transaksi' => $transaksi,
            'items' => $transaksi->items ?? [],
        ])->setPaper([0, 0, 226.77, 600], 'portrait')
            ->stream('receipt-'.$transaksi->id.'.pdf');
    }

    public function laporan(Request $request): View
    {
        [$startDate, $endDate] = $this->reportDates($request);
        $transactions = $this->reportQuery($startDate, $endDate)->paginate(20)->withQueryString();

        return view('admin.layout.wrapper', [
            'content' => 'admin.transaksi.laporan',
            'transaksi' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function cetakLaporan(Request $request): Response
    {
        [$startDate, $endDate] = $this->reportDates($request);
        $transactions = $this->reportQuery($startDate, $endDate)->get();

        return Pdf::loadView('admin.transaksi.printL', [
            'transaksi' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])->setPaper('a4', 'landscape')->stream('transaction-report.pdf');
    }

    public function destroy(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $this->ensureAccessible($request, $transaksi);

        if ($transaksi->status === 'selesai') {
            Alert::error('Gagal', 'Transaksi selesai tidak dapat dihapus.');
            return back();
        }

        DB::transaction(function () use ($transaksi): void {
            $lockedTransaction = Transaksi::whereKey($transaksi->id)->lockForUpdate()->firstOrFail();

            if ($lockedTransaction->status !== 'ditolak') {
                $this->restoreStock($lockedTransaction);
            }

            if ($lockedTransaction->bukti_pembayaran) {
                Storage::disk('local')->delete($lockedTransaction->bukti_pembayaran);
            }

            $lockedTransaction->delete();
        });

        Alert::success('Berhasil', 'Transaksi berhasil dihapus.');
        return redirect()->route('transaksi.index');
    }

    private function ensureAccessible(Request $request, Transaksi $transaksi): void
    {
        if ($request->user()->role === 'mitra') {
            abort_unless($transaksi->user_id === $request->user()->id, 403);
        }
    }

    private function restoreStock(Transaksi $transaksi): void
    {
        foreach ($transaksi->items ?? [] as $item) {
            Produk::whereKey($item['produk_id'])->lockForUpdate()->increment('stok', (float) $item['qty']);
        }
    }

    private function reserveStock(Transaksi $transaksi): void
    {
        foreach ($transaksi->items ?? [] as $item) {
            $product = Produk::whereKey($item['produk_id'])->lockForUpdate()->firstOrFail();
            $quantity = (float) $item['qty'];

            if ((float) $product->stok < $quantity) {
                abort(422, 'Stok tidak mencukupi untuk mengaktifkan kembali transaksi.');
            }

            $product->decrement('stok', $quantity);
        }
    }

    private function reportDates(Request $request): array
    {
        return [
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ];
    }

    private function reportQuery(string $startDate, string $endDate)
    {
        return Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->latest();
    }
}
