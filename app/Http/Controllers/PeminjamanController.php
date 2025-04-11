<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\peminjaman;
use App\Models\Buku;
use App\Models\Penerbit;
use App\Models\Penulis;
use App\Models\Kategori;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { {
            $peminjaman = peminjaman::orderBy('id', 'desc')->get();
            $buku = Buku::all();
            return view('user.peminjaman.index', compact('peminjaman', 'buku'));
        }
    }

    public function indexapi()
    {
        $peminjaman = Peminjaman::with(['buku'])->get();

        $res = [
            'success' => true,
            'message' => 'Daftar Peminjaman',
            'peminjamans' => $peminjaman,
        ];

        return response()->json($res, 200);
    }


    public function indexAdmin()
    {
        $buku = Buku::all();
        $user = Auth::user();
        $kategori = Kategori::all();
        $penulis = Penulis::all();
        $penerbit = Penerbit::all();
        $peminjaman = Peminjaman::with('user', 'buku')->latest()->paginate(10);
        return view('admin.peminjamanadmin.index', compact('buku', 'kategori', 'penulis', 'penerbit', 'peminjaman', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $peminjaman = peminjaman::orderBy('id', 'desc')->get();
        $batastanggal = Carbon::now()->addWeek()->format('Y-m-d');
        $sekarang = now()->format('Y-m-d');
        $buku = Buku::all();
        return view('user.peminjaman.create', compact('buku', 'sekarang', 'batastanggal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $peminjaman = new peminjaman();
        $peminjaman->nomor_peminjaman = 'PMJ-' . mt_rand(100000, 999999);
        $peminjaman->id_user = Auth::id();
        $peminjaman->id_buku = $request->id_buku;
        $peminjaman->jumlah = $request->jumlah;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->batas_pinjam = $request->batas_pinjam;
        $peminjaman->tanggal_kembali = $request->tanggal_kembali;
        $peminjaman->status =   'menunggu';
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam');
    }

    public function getPeminjaman($nomor_peminjaman)
    {
        $peminjaman = Peminjaman::where('nomor_peminjaman', $nomor_peminjaman)->with('buku')->first();

        if (!$peminjaman) {
            return response()->json(['error' => 'Peminjaman tidak ditemukan atau belum disetujui!'], 404);
        }

        return response()->json([
            'nama_peminjam' => $peminjaman->nama_peminjam,
            'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
            'batas_pinjam' => $peminjaman->batas_pinjam,
            'buku_dipinjam' => $peminjaman->buku->map(function ($buku) {
                return [
                    'id_buku' => $buku->id,
                    'judul' => $buku->judul,
                    'jumlah' => $buku->pivot->jumlah,
                ];
            })
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $peminjaman = peminjaman::with('buku')->findOrFail($id);
        return view('admin.peminjamanadmin.detail', compact('peminjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(peminjaman $peminjaman)
    {
        $buku = Buku::all();
        return view('user.peminjaman.edit', compact('peminjaman', 'buku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Ambil data peminjaman berdasarkan ID
        $peminjaman = Peminjaman::findOrFail($id);

        // Debugging: Check if 'id_user' exists in 'peminjaman'
        if (!$peminjaman->id_user) {
            // If no user found, log or return error
            return redirect()->route('peminjaman.index')->with('error', 'ID user tidak ditemukan pada peminjaman.');
        }

        // Cek apakah status yang dipilih adalah 'Kembalikan' (1)
        if ($request->status == 1) {
            // Buat data pengembalian baru
            $pengembalian = new Pengembalian();
            $pengembalian->id_user = $peminjaman->id_user;  // Ambil ID user dari peminjaman
            $pengembalian->id_peminjaman = $peminjaman->id; // Ambil ID peminjaman
            $pengembalian->tanggal_pengembalian = $peminjaman->id;  // Set tanggal pengembalian saat ini
            $pengembalian->status = 'menunggu'; // Status pengembalian dimulai dengan 'menunggu'
            $pengembalian->alasan_kembali = ''; // Kamu bisa menambahkan alasan jika perlu
            $pengembalian->denda = 0; // Tentukan denda jika perlu
            $pengembalian->save(); // Simpan data pengembalian

            // Update status peminjaman jika perlu
            $peminjaman->status = 'disetujui'; // Misalnya ganti status peminjaman menjadi dikembalikan
            $peminjaman->save();
        }

        // Redirect ke halaman yang diinginkan setelah update
        return redirect()->route('peminjaman.index')->with('success', 'Proses pengembalian berhasil.');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { {
            $minjem = peminjaman::findOrFail($id);
            $minjem->delete();
            return redirect()->route('peminjamanadmin.index');
        }
    }
}
