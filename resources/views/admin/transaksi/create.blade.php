<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
            color: #000000;
            font-family: 'Arial', sans-serif;
        }

        .btn-primary-custom {
            background-color: #4682b4;
            color: #fff;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-primary-custom:hover {
            background-color: #3a6b99;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #f44336;
            color: white;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-secondary:hover {
            background-color: #c62828;
            transform: scale(1.05);
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #ffffff;
            color: #000000;
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            font-size: 1rem;
            padding: 10px;
            color: #000000;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }

        .card {
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            background-color: #ffffff;
            margin-bottom: 20px;
        }

        .container {
            max-width: 1200px;
        }

        .input-group select,
        .form-control {
            background-color: #f7f7f7;
            border: 1px solid #d1d1d1;
            border-radius: 8px;
            padding: 12px;
            color: #000000;
            width: 100%;
            box-sizing: border-box;
        }

        .input-group button {
            background-color: #4682b4;
            color: white;
            border-radius: 8px;
            padding: 12px 15px;
        }

        .card-body {
            padding: 30px;
        }

        .text-end a {
            border-radius: 8px;
        }

        .alert {
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .col-lg-6 .card-body form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            width: 100%;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .text-end a,
        .text-end button {
            width: auto;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
        }

        .input-group select {
            width: 80%;
        }

        .input-group button {
            width: 18%;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Pilih Produk</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-4">
                            <div class="input-group">
                                <select name="produk_id" class="form-select">
                                    <option value="">
                                        -- Pilih Produk --
                                    </option>
                                    @foreach ($produk as $item)
                                        <option value="{{ $item->id }}" {{ request('produk_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} (Stok: {{ rtrim(rtrim(number_format($item->stok, 2, '.', ''), '0'), '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary-custom ms-2">Pilih</button>
                            </div>
                        </form>

                        @php
                            $selectedProduk = null;
                            if (request('produk_id')) {
                                $selectedProduk = $produk->find(request('produk_id'));
                            }
                        @endphp
                        
                        <form action="{{ route('transaksi.addItem', $transaksi->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $selectedProduk->id ?? '' }}">

                            @if($selectedProduk)
                                <div class="text-center mb-3">
                                    <img src="{{ asset($selectedProduk->gambar ?? 'image/product-placeholder.svg') }}" class="product-image" alt="Produk">
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" value="{{ $selectedProduk->name ?? '' }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control" id="harga" value="Rp. {{ number_format($selectedProduk->harga ?? 0, 0, ',', '.') }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok Tersedia</label>
                                <input type="text" class="form-control" value="{{ rtrim(rtrim(number_format($selectedProduk->stok ?? 0, 2, '.', ''), '0'), '.') }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Qty</label>
                                <input type="number" 
                                    name="qty" 
                                    id="qty" 
                                    class="form-control text-center" 
                                    value="1" 
                                    min="0.01"
                                    step="0.01" 
                                    max="{{ $selectedProduk->stok ?? 1 }}"
                                    {{ $selectedProduk ? '' : 'disabled' }}>
                            </div>
                            
                            <div class="text-end mt-4">
                                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary-custom" {{ $selectedProduk ? '' : 'disabled' }}>Tambah Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Belanja</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Nama</th>
                                    <th>Berat/Kg</th>
                                    <th>Subtotal</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksi->items ?? [] as $index => $item)
                                    <tr>
                                        <td>
                                            @php
                                                $produkItem = \App\Models\Produk::find($item['produk_id']);
                                                $gambar = optional($produkItem)->gambar ?? 'image/product-placeholder.svg';
                                            @endphp
                                            <img src="{{ asset($gambar) }}" class="product-image" alt="Produk">
                                        </td>
                                        <td>{{ $item['produk_name'] }}</td>
                                        <td>{{ rtrim(rtrim(number_format($item['qty'], 2, '.', ''), '0'), '.') }}</td>
                                        <td>Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('transaksi.removeItem', ['transaksi' => $transaksi->id, 'produk' => $item['produk_id']]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Keranjang masih kosong.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <strong>Total Belanja :</strong>
                                    <span id="totalBelanja" class="text-end"><b>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</b></span>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <a href="{{ route('transaksi.showPaymentPage', $transaksi->id) }}" class="btn btn-success {{ $transaksi->total == 0 ? 'disabled' : '' }}">
                                Selanjutnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @if (session('stok_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Stok Tidak Cukup',
            text: "{{ session('stok_error') }}"
        });
    </script>
    @endif
    
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: "{{ session('success') }}"
        });
    </script>
    @endif
    
    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}"
        });
    </script>
    @endif
    

    <script>
        // SweetAlert konfirmasi untuk Checkout
        document.getElementById('checkoutBtn').addEventListener('click', function(e) {
            e.preventDefault(); 
            let form = this.closest('form');

            Swal.fire({
                title: 'Apakah Pesanan Sudah Sesuai?',
                text: 'Apakah ingin melanjutkan proses pemesanan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>