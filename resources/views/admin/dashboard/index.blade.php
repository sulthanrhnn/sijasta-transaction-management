<div class="container-fluid pt-3">
  <div class="alert alert-success">Selamat datang, {{ auth()->user()->name }}.</div>
  <div class="row">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-primary">
        <div class="inner"><h4>Rp {{ number_format($totalPendapatanTransaksi, 0, ',', '.') }}</h4><p>Total Pendapatan</p></div>
        <div class="icon"><i class="fas fa-wallet"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner"><h3>{{ $jumlahProduk }}</h3><p>Jumlah Produk</p></div>
        <div class="icon"><i class="fas fa-boxes"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner"><h3>{{ $jumlahMitra }}</h3><p>Jumlah Mitra</p></div>
        <div class="icon"><i class="fas fa-handshake"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner"><h3>{{ $totalTransaksiSelesai }}</h3><p>Transaksi Selesai</p></div>
        <div class="icon"><i class="fas fa-check-circle"></i></div>
      </div>
    </div>
  </div>
</div>
