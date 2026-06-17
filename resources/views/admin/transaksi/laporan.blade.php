<div class="container-fluid pt-3">
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Laporan Transaksi Selesai</strong>
      <a href="{{ route('transaksi.print', ['start_date' => $start_date, 'end_date' => $end_date]) }}" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('transaksi.laporan') }}" class="row mb-3">
        <div class="col-md-4"><label>Tanggal Awal</label><input type="date" name="start_date" class="form-control" value="{{ $start_date }}"></div>
        <div class="col-md-4"><label>Tanggal Akhir</label><input type="date" name="end_date" class="form-control" value="{{ $end_date }}"></div>
        <div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary" type="submit">Filter</button></div>
      </form>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead><tr><th>No</th><th>ID</th><th>Pelanggan</th><th>Tanggal</th><th>Produk</th><th>Total</th></tr></thead>
          <tbody>
            @forelse ($transaksi as $item)
              <tr>
                <td>{{ $transaksi->firstItem() + $loop->index }}</td>
                <td>TR{{ $item->id }}</td>
                <td>{{ $item->kasir_name }}</td>
                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                <td>
                  @forelse ($item->items ?? [] as $product)
                    <div>{{ $product['produk_name'] }} — {{ rtrim(rtrim(number_format($product['qty'], 2, '.', ''), '0'), '.') }}</div>
                  @empty
                    <span class="text-muted">Tidak ada produk</span>
                  @endforelse
                </td>
                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Tidak ada transaksi selesai.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">{{ $transaksi->links('pagination::bootstrap-4') }}</div>
  </div>
</div>
