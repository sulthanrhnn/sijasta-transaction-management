<div class="container-fluid pt-3">
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Daftar Mitra</strong>
      <a href="{{ route('mitra.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover mb-0">
        <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Nomor Telepon</th><th>Alamat</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse ($mitra as $item)
            <tr>
              <td>{{ $mitra->firstItem() + $loop->index }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->email }}</td>
              <td>{{ $item->no_hp }}</td>
              <td>{{ $item->alamat }}</td>
              <td class="d-flex">
                <a href="{{ route('mitra.edit', $item) }}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i></a>
                <form action="{{ route('mitra.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data mitra ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada data mitra.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $mitra->links('pagination::bootstrap-4') }}</div>
  </div>
</div>
