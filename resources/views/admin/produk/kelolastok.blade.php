{{-- File: resources/views/admin/produk/kelolastok.blade.php --}}

<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5><b>Riwayat Kelola Produk</b></h5>
                    <hr>

                    <table class="table mt-3 table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Tambah</th>
                                <th>Stok Sebelum</th>
                                <th>Stok Sesudah</th>
                                <th>Aktivitas</th>
                                <th>Oleh Pengguna</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelolaStoks as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($kelolaStoks->currentPage() - 1) * $kelolaStoks->perPage() }}</td>
                                <td>{{ $item->produk->name ?? 'Produk Dihapus' }}</td>
                                <td>{{ $item->jumlah_stok_tambah }}</td>
                                <td>{{ $item->stok_sebelum }}</td>
                                <td>{{ $item->stok_sesudah }}</td>
                                <td>{{ $item->aktivitas }}</td>
                                <td>{{ $item->user->name ?? 'Pengguna Dihapus' }}</td>
                                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-center">
                        {{ $kelolaStoks->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>