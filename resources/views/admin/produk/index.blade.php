{{-- File: resources/views/admin/produk/index.blade.php (kode tetap sama) --}}

<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5><b>Produk</b></h5>
                    @if (in_array(Auth::user()->role, ['admin', 'asisten']))     
                        <a href="{{ route('produk.create') }}" class="btn btn-primary"><i class="fas fa-plus"> Tambah </i></a>
                    @endif
                    <hr>

                    <table class="table mt-3">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga/Kg</th>
                            <th>Stok</th>
                            <th>Gambar Produk</th>
                            <th>Aksi</th>
                        </tr>

                        @foreach ($produk as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($produk->currentPage() - 1) * $produk->perPage() }}</td>
                                <td>{{ $item->name }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="{{ $item->stok < 10 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold' }}">
                                        {{ rtrim(rtrim(number_format($item->stok, 2, '.', ''), '0'), '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->gambar)
                                        <img src="{{ asset($item->gambar) }}" alt="{{ $item->name }}" width="75px">
                                    @else
                                        <img src="{{ asset('image/product-placeholder.svg') }}" alt="Placeholder" width="75px">
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        {{-- Tautan ini akan mengarah ke form edit yang baru kita buat --}}
                                        <a href="{{ route('produk.edit', $item) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'asisten')
                                            <form action="{{ route('produk.destroy', $item) }}" method="POST" id="deleteForm-{{ $item->id }}" class="ml-1">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(event, '{{ $item->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    {{-- Pagination Info + Navigation --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $produk->firstItem() }} - {{ $produk->lastItem() }} dari {{ $produk->total() }} data
                            </small>
                        </div>
                        <div>
                            {{ $produk->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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