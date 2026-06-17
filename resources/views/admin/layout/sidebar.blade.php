<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="{{ auth()->user()->role === 'mitra' ? route('mitra.dashboard') : route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('image/sijasta-logo.svg') }}" class="brand-image img-circle elevation-3" alt="SIJASTA">
        <span class="brand-text font-weight-light">SIJASTA</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @if (in_array(Auth::user()->role, ['admin', 'asisten']))
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i><p>Dashboard</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i><p>Pengguna</p>
                        </a>
                    </li>
                @endif

                @if (in_array(Auth::user()->role, ['admin', 'asisten']))
                    <li class="nav-item">
                        <a href="{{ route('mitra.index') }}" class="nav-link {{ request()->routeIs('mitra.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake"></i><p>Mitra</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i><p>Transaksi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.index', 'produk.create', 'produk.edit') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i><p>Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kelola-stok') }}" class="nav-link {{ request()->routeIs('admin.kelola-stok') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i><p>Log Stok</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
<div class="content-wrapper">
