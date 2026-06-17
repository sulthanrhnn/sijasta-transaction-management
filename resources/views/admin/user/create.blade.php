<div class="container-fluid pt-3">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-header"><strong>{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}</strong></div>
        <form action="{{ isset($user) ? route('user.update', $user) : route('user.store') }}" method="POST">
          @csrf
          @isset($user) @method('PUT') @endisset
          <div class="card-body">
            <div class="form-group">
              <label>Nama</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
              @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Konfirmasi Password</label>
              <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
            </div>
            <div class="form-group">
              <label>Role</label>
              <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                @foreach (['admin' => 'Admin', 'asisten' => 'Asisten', 'mitra' => 'Mitra'] as $value => $label)
                  <option value="{{ $value }}" @selected(old('role', $user->role ?? '') === $value)>{{ $label }}</option>
                @endforeach
              </select>
              @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
