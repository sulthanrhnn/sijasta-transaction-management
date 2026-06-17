{{-- admin/transaksi/indexmitra.blade.php --}}
@extends('admin.layout.pagemitra')

@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="description" content="SIJASTA transaction management portfolio demo" />
    <meta name="author" content="M. Sultan Raihan Attalla" />
    <title>SIJASTA | Partner Transactions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/mitra-style.css') }}">

    <style>
        /* Tambahan style untuk status baru */
        .badge-pending {
            background-color: #FFD700;
            color: black;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .badge-selesai {
            background-color: #40E0D0;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .badge-diproses {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .badge-ditolak {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>

<div class="container-fluid pt-4">
    <h5 class="mb-3"><strong>Riwayat Transaksi</strong></h5>

    <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-shopping-cart"></i> Pesan Barang Sekarang
    </a>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="GET" action="{{ url('/admin/transaksi') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ url('/admin/transaksi') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Produk Dipesan/Berat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Log Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @forelse ($item->items ?? [] as $detail)
                                <div>{{ $detail['produk_name'] }} ({{ rtrim(rtrim(number_format($detail['qty'], 2, '.', ''), '0'), '.') }} Kg)</div>
                            @empty
                                <div>Tidak ada produk</div>
                            @endforelse
                        </td>
                        <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <span class="badge badge-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>
                            @if($item->log_status && is_array($item->log_status))
                                <ul class="list-unstyled mb-0">
                                @foreach($item->log_status as $log)
                                    @php
                                        // Gunakan operator null coalescing untuk kompatibilitas data lama
                                        $logStatus = $log['status_baru'] ?? ($log['status'] ?? 'unknown');
                                    @endphp
                                    <li class="mb-1 p-1 border-bottom">
                                        <span class="badge badge-{{ $logStatus }}">{{ ucfirst($logStatus) }}</span>
                                        <small class="text-muted d-block">oleh: {{ $log['oleh'] ?? 'unknown' }}</small>
                                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($log['waktu'] ?? now())->format('d-m-Y H:i') }}</small>
                                    </li>
                                @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Tidak ada log</span>
                            @endif
                        </td>
                        <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td>
                            @if($item->status === 'selesai')
                                <a href="{{ route('transaksi.struk', $item->id) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-receipt"></i> Cetak Struk
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <small class="text-muted">
                Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} data
            </small>
        </div>
        <div>
            {{ $transaksi->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection