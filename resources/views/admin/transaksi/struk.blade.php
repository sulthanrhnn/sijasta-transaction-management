<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Struk Transaksi</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    td, th { padding: 4px; }
    .center { text-align: center; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <div class="center">
    <h3>SIJASTA DEMO STORE</h3>
    <p>Portfolio Demonstration</p>
    <p>{{ $transaksi->created_at->format('d-m-Y H:i') }}</p>
    <p>Pelanggan: {{ $transaksi->kasir_name }}</p>
  </div>
  <hr>
  <table>
    <thead><tr><th>Produk</th><th>Qty</th><th class="right">Subtotal</th></tr></thead>
    <tbody>
      @foreach ($items as $item)
        <tr><td>{{ $item['produk_name'] }}</td><td>{{ rtrim(rtrim(number_format($item['qty'], 2, '.', ''), '0'), '.') }}</td><td class="right">{{ number_format($item['subtotal'], 0, ',', '.') }}</td></tr>
      @endforeach
    </tbody>
  </table>
  <hr>
  <p class="right"><strong>Total: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong></p>
  <p class="center">Terima kasih.</p>
</body>
</html>
