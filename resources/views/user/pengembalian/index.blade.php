@extends('layouts.backend')
<title>Perpustakaan - Pengembalian Buku</title>
@section('content')
<div class="col-12 col-xl-12">
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Data Peminjaman</h5>
            <form class="row g-3" method="POST" action="{{ route('pengembalian.store') }}">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">No Peminjaman Buku</label>
                    <input type="text" id="no_peminjaman" class="form-control" name="no_peminjaman" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" id="nama_peminjam" class="form-control" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="text" id="tanggal_pinjam" class="form-control" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Batas Pinjam</label>
                    <input type="text" id="batas_pinjam" class="form-control" readonly>
                </div>

                <hr>
                <h5>Detail Buku yang Dipinjam</h5>
                <div class="row" id="book-list">
                    <p id="placeholder-text" class="text-muted">Silakan masukkan No Peminjaman untuk melihat detail buku.</p>
                </div>

                <hr>
                <h5>Denda</h5>

                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('no_peminjaman').addEventListener('change', async function() {
                        let noPeminjaman = this.value.trim();
                        let errorDiv = document.getElementById('error-message');
                        let bookList = document.getElementById('book-list');

                        if (!noPeminjaman) return;
                        try {
                            let response = await fetch(`/api/get-peminjaman/${noPeminjaman}`);
                            let data = await response.json();

                            if (!response.ok) throw new Error("Peminjaman tidak ditemukan atau belum disetujui!");

                            document.getElementById('nama_peminjam').value = data.nama_peminjam;
                            document.getElementById('tanggal_pinjam').value = data.tanggal_pinjam;
                            document.getElementById('batas_pinjam').value = data.batas_pinjam;

                            bookList.innerHTML = '';
                            data.buku_dipinjam.forEach((buku) => {
                                bookList.innerHTML += `
                                    <div class="col-md-10">
                                        <label class="form-label">Judul Buku</label>
                                        <input type="text" class="form-control" value="${buku.judul}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" name="jumlah_kembali[${buku.id_buku}]" value="${buku.jumlah}" required>
                                    </div>
                                    <input type="hidden" name="id_buku[]" value="${buku.id_buku}">
                                `;
                            });

                            errorDiv.style.display = "none";

                        } catch (error) {
                            errorDiv.textContent = error.message;
                            errorDiv.style.display = "block";

                            document.getElementById('nama_peminjam').value = "";
                            document.getElementById('tanggal_pinjam').value = "";
                            document.getElementById('batas_pinjam').value = "";
                            bookList.innerHTML = "";
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>

@endsection
