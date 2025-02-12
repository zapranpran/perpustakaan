<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{

    public function index()
    {
        $pengembalian = Pengembalian::orderBy('id', 'desc')->get();
        $user = Auth::user();
        return view('user.pengembalian.index', compact('user', 'pengembalian'));
    }

    public function store(Request $request)
    {
        $peminjaman = Peminjaman::where('no_peminjaman', $request->no_peminjaman)
            ->where('status_pinjam', 'disetujui')
            ->first();

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Peminjaman tidak ditemukan atau belum disetujui.');
        }

        // Simpan data pengembalian
        $pengembalian = new Pengembalian();
        $pengembalian->id_user = Auth::id();
        $pengembalian->id_peminjaman = $peminjaman->id;
        $pengembalian->tanggal_penggembalian = now();
        $pengembalian->denda = 0;
        $pengembalian->status_kembali = 'menunggu';
        $pengembalian->save();

        // Update stok buku yang dikembalikan
        foreach ($request->id_buku as $key => $id_buku) {
            $jumlah_kembali = $request->jumlah_kembali[$id_buku] ?? 0;
            $buku = Buku::find($id_buku);

            if ($buku) {
                $buku->jumlah_buku += $jumlah_kembali;
                $buku->save();
            }
        }

        return redirect()->route('adminpengembalian')->with('success', 'Pengembalian berhasil disimpan dan menunggu persetujuan.');
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
