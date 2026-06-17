<div class="container-fluid pt-3">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-header"><strong>{{ isset($mitra) ? 'Edit Mitra' : 'Tambah Mitra' }}</strong></div>
        <form action="{{ isset($mitra) ? route('mitra.update', $mitra) : route('mitra.store') }}" method="POST">
          @csrf
          @isset($mitra) @method('PUT') @endisset
          <div class="card-body">
            @foreach ([
              ['name','Nama','text'],
              ['email','Email','email'],
              ['no_hp','Nomor Telepon','text'],
              ['alamat','Alamat','text']
            ] as [$field,$label,$type])
              <div class="form-group">
                <label>{{ $label }}</label>
                <input type="{{ $type }}" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field, $mitra->{$field} ?? '') }}" required>
                @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            @endforeach
          </div>
          <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
