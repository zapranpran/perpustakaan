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
                        <input type="text" id="id_user" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="text" id="tanggal_pinjam" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Batas Pinjam</label>
                        <input type="text" id="batas_pinjam" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pengembalian</label>
                        <input type="date" id="tanggal_pengembalian" class="form-control" name="tanggal_pengembalian"
                            required>
                    </div>

                    <hr>
                    <h5>Detail Buku yang Dipinjam</h5>
                    <div class="row" id="book-list">
                        <p id="placeholder-text" class="text-muted">Silakan masukkan No Peminjaman untuk melihat detail
                            buku.</p>
                    </div>

                    <hr>
                    <h5>Denda</h5>
                    <div class="col-md-6">
                        <label class="form-label">Total Denda (Rp)</label>
                        <input type="text" id="total_denda" class="form-control" name="total_denda" readonly>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('no_peminjaman').addEventListener('change', async function(event) {
                            event.preventDefault(); // Cegah event bawaan form agar tidak refresh halaman

                            let noPeminjaman = this.value.trim();
                            let bookList = document.getElementById('book-list');
                            let placeholderText = document.getElementById('placeholder-text');

                            if (!noPeminjaman) return;

                            try {
                                let response = await fetch(`/api/get-peminjaman/${noPeminjaman}`);
                                let data = await response.json();

                                if (!response.ok) throw new Error(
                                    "Peminjaman tidak ditemukan atau belum disetujui!");

                                // Isi data ke form
                                document.getElementById('nama_peminjam').value = data.nama_peminjam;
                                document.getElementById('tanggal_pinjam').value = data.tanggal_pinjam;
                                document.getElementById('batas_pinjam').value = data.batas_pinjam;

                                // Set tanggal pengembalian hari ini
                                let today = new Date().toISOString().split('T')[0];
                                document.getElementById('tanggal_pengembalian').value = today;

                                // Hapus placeholder text
                                placeholderText.style.display = "none";
                                bookList.innerHTML = '';

                                // Tampilkan buku yang dipinjam
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

                                // Hitung denda jika ada keterlambatan
                                hitungDenda();

                            } catch (error) {
                                alert(error.message);

                                // Reset form jika terjadi error
                                document.getElementById('nama_peminjam').value = "";
                                document.getElementById('tanggal_pinjam').value = "";
                                document.getElementById('batas_pinjam').value = "";
                                bookList.innerHTML = "";
                                placeholderText.style.display = "block";
                            }
                        });

                        document.getElementById('tanggal_pengembalian').addEventListener('change', hitungDenda);

                        function hitungDenda() {
                            let batasPinjam = document.getElementById('batas_pinjam').value;
                            let tanggalPengembalian = document.getElementById('tanggal_pengembalian').value;
                            let totalDendaInput = document.getElementById('total_denda');
                            let tarifDendaPerHari = 1000; // Ubah sesuai kebijakan perpustakaan

                            if (!batasPinjam || !tanggalPengembalian) {
                                totalDendaInput.value = 0;
                                return;
                            }

                            let batasDate = new Date(batasPinjam);
                            let pengembalianDate = new Date(tanggalPengembalian);

                            if (pengembalianDate > batasDate) {
                                let keterlambatanHari = Math.ceil((pengembalianDate - batasDate) / (1000 * 60 * 60 * 24));
                                let totalDenda = keterlambatanHari * tarifDendaPerHari;
                                totalDendaInput.value = totalDenda;
                            } else {
                                totalDendaInput.value = 0;
                            }
                        }
                    });
                </script>

            </div>
        </div>
    </div>
@endsection
