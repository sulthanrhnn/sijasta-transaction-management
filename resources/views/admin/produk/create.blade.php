{{-- File: resources/views/admin/produk/create.blade.php --}}

<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5><b>Tambah Produk Baru</b></h5>
                    <hr>

                    {{-- Form ini hanya untuk produk baru --}}
                    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <label for="name">Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Produk" value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="harga" class="mt-3">Harga/Kg</label>
                        <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="Harga/Kg" value="{{ old('harga') }}">
                        @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="stok" class="mt-3">Stok Awal</label>
                        <input type="number" name="stok" id="stok" step="0.01" min="0" class="form-control @error('stok') is-invalid @enderror" placeholder="Stok Produk" value="{{ old('stok') }}">
                        @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <label for="gambar" class="mt-3">Gambar Produk</label>
                        <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                        @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="d-flex mt-3">
                            <a href="{{ route('produk.index') }}" class="btn btn-info me-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>