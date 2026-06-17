{{-- File: resources/views/admin/produk/edit.blade.php --}}

<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5><b>Edit Produk: {{ $produk->name }}</b></h5>
                    <hr>

                    {{-- Form ini hanya untuk edit produk --}}
                    <form action="{{ route('produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        
                        <label for="name">Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Produk" value="{{ $produk->name }}" readonly>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="harga" class="mt-3">Harga/Kg</label>
                        <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="Harga/Kg" value="{{ $produk->harga }}" readonly>
                        @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="stok" class="mt-3">Stok</label>
                        <input type="number" name="stok" id="stok" step="0.01" min="0" class="form-control @error('stok') is-invalid @enderror" placeholder="Stok Produk" value="{{ $produk->stok }}">
                        @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <label for="gambar" class="mt-3">Gambar Produk</label>
                        @if($produk->gambar)
                        <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->name }}" class="d-block mb-2" width="100px">
                        @endif
                        <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                        @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="d-flex mt-3">
                            <a href="{{ route('produk.index') }}" class="btn btn-info me-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>