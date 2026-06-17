<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Transaksi SIJASTA</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
    h1 { text-align: center; margin-bottom: 4px; }
    .period { text-align: center; margin-bottom: 18px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #9ca3af; padding: 6px; vertical-align: top; }
    th { background: #e5e7eb; }
    .right { text-align: right; }
    .summary { margin-top: 14px; font-weight: bold; text-align: right; }
  </style>
</head>
<body>
  <h1>Laporan Transaksi SIJASTA</h1>
  <div class="period">Periode {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}</div>
  <table>
    <thead><tr><th>No</th><th>ID</th><th>Pelanggan</th><th>Tanggal</th><th>Produk</th><th>Status</th><th>Total</th></tr></thead>
    <tbody>
      @forelse ($transaksi as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>TR{{ $item->id }}</td>
          <td>{{ $item->kasir_name }}</td>
          <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
          <td>
            @forelse ($item->items ?? [] as $product)
              <div>{{ $product['produk_name'] }} ({{ rtrim(rtrim(number_format($product['qty'], 2, '.', ''), '0'), '.') }})</div>
            @empty
              -
            @endforelse
          </td>
          <td>{{ ucfirst($item->status) }}</td>
          <td class="right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center">Tidak ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="summary">Total Pendapatan: Rp {{ number_format($transaksi->sum('total'), 0, ',', '.') }}</div>
</body>
</html>
