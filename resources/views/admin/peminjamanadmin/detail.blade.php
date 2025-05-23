@extends('layouts.backend')
@section('content')
    <div class="card m-3" style="padding-top: 50px">
        <div class="card-body">
            <h4 class="card-title">Detail Peminjaman</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Nama Buku:</strong>
                    <p>{{ $peminjaman->buku->judul }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Nama Peminjam:</strong>
                    <p>{{ $peminjaman->nama_peminjam }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Jumlah:</strong>
                    <p>{{ $peminjaman->jumlah }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Tanggal Peminjaman:</strong>
                    <p>{{ $peminjaman->tanggal_pinjam }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>BATAS PEMINJAMAN:</strong>
                    <p>{{ $peminjaman->batas_pinjam }}</p>
                </div>
                <div class="col-md-6">
                    <strong>TANGGAL PENGEMBALIAN:</strong>
                    <p>{{ $peminjaman->tanggal_kembali }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')

                        {{-- Jika statusnya disetujui --}}
                        @if ($peminjaman->status == 'menunggu')
                            <button type="submit" name="status" value="disetujui" class="btn btn-success btn-sm">Terima</button>
                            <button type="submit" name="status" value="ditolak" class="btn btn-danger btn-sm">Tolak</button>
                        @else
                            {{-- Tambahkan tombol lain jika status tidak dikenali --}}
                        @endif
                    </form>
                </div>
            </div>

            {{-- <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="btn btn-warning">Edit</a> --}}
            <a href="{{ route('peminjamanadmin.admin') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
