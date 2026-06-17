@extends('admin.layout.pagemitra')

@section('content')
<style>
  .produk-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; }
  .produk-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 18px rgba(15,23,42,.08); }
  .produk-img { width: 100%; height: 180px; object-fit: cover; background: #e5e7eb; }
  .produk-body { padding: 18px; }
  .produk-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; }
  .produk-info { color: #475569; margin-bottom: 5px; }
</style>

<div class="alert alert-success">Selamat datang, {{ auth()->user()->name }}.</div>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Produk Tersedia</h2>
  <a href="{{ route('transaksi.create') }}" class="btn btn-primary"><i class="fas fa-cart-plus"></i> Buat Pesanan</a>
</div>

@if($produk->count())
  <div class="produk-grid">
    @foreach ($produk as $item)
      <article class="produk-card">
        <img src="{{ asset($item->gambar ?: 'image/product-placeholder.svg') }}" alt="{{ $item->name }}" class="produk-img">
        <div class="produk-body">
          <div class="produk-title">{{ $item->name }}</div>
          <div class="produk-info"><strong>Harga:</strong> Rp {{ number_format($item->harga, 0, ',', '.') }} / kg</div>
          <div class="produk-info"><strong>Stok:</strong> {{ rtrim(rtrim(number_format($item->stok, 2, '.', ''), '0'), '.') }} kg</div>
        </div>
      </article>
    @endforeach
  </div>
  <div class="mt-4">{{ $produk->links('pagination::bootstrap-4') }}</div>
@else
  <div class="alert alert-secondary">Belum ada produk tersedia.</div>
@endif
@endsection
