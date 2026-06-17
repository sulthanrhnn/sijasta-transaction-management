<div class="container-fluid pt-3">
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Daftar Pengguna</strong>
      <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Tambah</a>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover mb-0">
        <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse ($user as $item)
            <tr>
              <td>{{ $user->firstItem() + $loop->index }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->email }}</td>
              <td><span class="badge badge-info">{{ ucfirst($item->role) }}</span></td>
              <td class="d-flex">
                <a href="{{ route('user.edit', $item) }}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i></a>
                <form action="{{ route('user.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada pengguna.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $user->links('pagination::bootstrap-4') }}</div>
  </div>
</div>
