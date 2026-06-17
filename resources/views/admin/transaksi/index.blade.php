<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Manajemen Transaksi</strong></h5>

                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-plus"></i> Tambah
                    </a>

                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle"></i> {{ session('error') }}
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

                    <form action="{{ route('transaksi.print') }}" method="GET" target="_blank" class="mb-3">
    <input type="hidden" name="start_date" value="{{ $start_date }}">
    <input type="hidden" name="end_date" value="{{ $end_date }}">
    <button type="submit" class="btn btn-danger">
        <i class="fa fa-file-pdf"></i> Cetak Laporan
    </button>
</form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Produk Dipesan/Berat</th>
                                    <th>Tanggal</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Status & Keterangan</th>
                                    <th>Log Status</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksi as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kasir_name }}</td>
                                        <td>
                                            @forelse ($item->items ?? [] as $detail)
                                                <div>{{ $detail['produk_name'] }} ({{ rtrim(rtrim(number_format($detail['qty'], 2, '.', ''), '0'), '.') }} Kg)</div>
                                            @empty
                                                <div>Tidak ada produk</div>
                                            @endforelse
                                        </td>
                                        <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            @if ($item->bukti_pembayaran)
                                                <a href="{{ route('transaksi.paymentProof', $item) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                                    Lihat Bukti
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada bukti</span>
                                            @endif
                                        </td>
                                        <td style="width: 250px;">
                                            <form action="{{ route('transaksi.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-2">
                                                    <label for="status-{{ $item->id }}" class="form-label mb-1">Status</label>
                                                    <select id="status-{{ $item->id }}" name="status" class="form-select form-select-sm">
                                                        <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="diproses" {{ $item->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                        <option value="selesai" {{ $item->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                        <option value="ditolak" {{ $item->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="keterangan-{{ $item->id }}" class="form-label mb-1">Keterangan</label>
                                                    <textarea id="keterangan-{{ $item->id }}" name="keterangan" class="form-control form-control-sm" rows="2" placeholder="Tulis catatan di sini...">{{ $item->keterangan ?? '' }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-save"></i> Simpan
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            @if($item->log_status && is_array($item->log_status))
                                                <ul class="list-unstyled mb-0">
                                                @foreach($item->log_status as $log)
                                                    @php
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
                                            <div class="d-flex gap-2">
                                                @if($item->status === 'selesai')
                                                <a href="{{ route('transaksi.struk', $item->id) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                                @endif
                                                <form action="{{ route('transaksi.destroy', $item) }}" method="POST" id="deleteForm-{{ $item->id }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(event, '{{ $item->id }}')" {{ $item->status === 'selesai' ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Belum ada data transaksi.</td>
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
            </div>
        </div>
    </div>
</div>

<style>
/* CSS Anda sebelumnya */
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event, id) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + id).submit();
            } else {
                Swal.fire('Penghapusan dibatalkan!', '', 'info');
            }
        });
    }
</script>