<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .card-header {
            background-color: #4682b4;
            color: #fff;
            text-align: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Selesaikan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-4">Daftar Belanja</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Nama</th>
                                    <th>Berat/Kg</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->items as $item)
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
                                        <td>Rp. {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Belanja:</strong></td>
                                    <td><strong>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <hr>

                        <h4 class="mt-4 mb-3">Informasi Pembayaran</h4>
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading">Catatan Transfer:</h6>
                            <p class="mb-0">Untuk demonstrasi, gunakan rekening fiktif berikut:</p>
                            <ul class="mb-0">
                                <li>Demo Bank: 0000000000 (a.n. SIJASTA Demo)</li>
                            </ul>
                        </div>

                        <form action="{{ route('transaksi.processPayment', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                                <input class="form-control" type="file" id="payment_proof" name="payment_proof" required>
                                @error('payment_proof')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success">Selesaikan Pesanan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.js"></script>
    @include('sweetalert::alert')
</body>
</html>