<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIJASTA | Partner Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('css/mitra-style.css') }}">
  <style>
    html, body { min-height: 100%; margin: 0; font-family: 'Segoe UI', sans-serif; background: #f5f7fb; }
    body { display: flex; flex-direction: column; }
    .topbar { background: #2563eb; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.12); }
    .topbar .left, .topbar .right { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
    .topbar a, .topbar button { color: #fff; text-decoration: none; font-weight: 600; border: 0; background: transparent; }
    .topbar form.search { display: flex; align-items: center; margin-left: 12px; }
    .topbar input[type=text] { padding: 7px 10px; border-radius: 6px 0 0 6px; border: none; outline: none; }
    .topbar .search button { padding: 7px 11px; background: #1d4ed8; border-radius: 0 6px 6px 0; }
    main.container { flex: 1; padding-top: 24px; padding-bottom: 24px; }
    footer { background: #111827; color: #fff; padding: 12px; text-align: center; margin-top: auto; }
    @media (max-width: 700px) { .topbar { align-items: flex-start; flex-direction: column; } .topbar form.search { margin-left: 0; } }
  </style>
</head>
<body>
  <div class="topbar">
    <div class="left">
      <a href="{{ route('mitra.dashboard') }}"><i class="fas fa-home"></i> Beranda</a>
      <a href="{{ route('transaksi.index') }}"><i class="fas fa-receipt"></i> Transaksi Saya</a>
      <form class="search" action="{{ route('produk.search') }}" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk...">
        <button type="submit"><i class="fas fa-search"></i></button>
      </form>
    </div>
    <div class="right">
      <span>{{ auth()->user()->name }}</span>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
      </form>
    </div>
  </div>
  <main class="container">@yield('content')</main>
  <footer>&copy; {{ now()->year }} SIJASTA Portfolio Demo</footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
