@extends('layouts.admin2')

@section('content')
<div class="container">
    {{-- PINDAHKAN NOTIFIKASI KE SINI (DI LUAR LOOP) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">➕ Tambah Transaksi</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konsumen</label>
                        <select name="konsumen_id" class="form-control" required>
                            <option value="">-- Pilih Konsumen --</option>
                            @foreach($konsumens as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Produk</label>
                        <select name="produk_id" id="produk_id" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id }}" data-stok="{{ $p->stok }}" {{ $p->stok <= 0 ? 'disabled' : '' }}>
                                    {{ $p->nama }} - (Stok: {{ $p->stok }}) - Rp {{ number_format($p->harga,0,',','.') }} 
                                    {{ $p->stok <= 0 ? ' [HABIS]' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small id="stok-info" class="text-danger fw-bold"></small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="qty" id="qty_input" class="form-control" min="1" value="1" required>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK VALIDASI INSTAN DI SISI CLIENT --}}
<script>
    document.getElementById('produk_id').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];
        let stok = selected.getAttribute('data-stok');
        let info = document.getElementById('stok-info');
        let inputQty = document.getElementById('qty_input');

        if (stok) {
            info.textContent = "Sisa stok tersedia: " + stok;
            inputQty.setAttribute('max', stok); // User tidak bisa input lebih dari stok via keyboard
        } else {
            info.textContent = "";
        }
    });
</script>
@endsection