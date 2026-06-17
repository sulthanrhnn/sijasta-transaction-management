<div class="container-fluid pt-3">
  <div class="card shadow-sm">
    <div class="card-header"><strong>Produk Terjual</strong></div>
    <div class="card-body">
      <form action="{{ route('produk.detail') }}" method="GET" class="row mb-3">
        <div class="col-md-4">
          <label>Tanggal Awal</label>
          <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-4">
          <label>Tanggal Akhir</label>
          <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-primary">Filter</button>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead><tr><th>No</th><th>Nama Produk</th><th>Jumlah Terjual</th></tr></thead>
          <tbody>
            @forelse ($produkTerjual as $produk)
              <tr><td>{{ $loop->iteration }}</td><td>{{ $produk->produk_name }}</td><td>{{ rtrim(rtrim(number_format($produk->total_qty, 2, '.', ''), '0'), '.') }}</td></tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">Belum ada transaksi selesai pada periode ini.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
