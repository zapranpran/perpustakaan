<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjamens';

    use HasFactory;
    protected $fillable = ['id', 'nomor_peminjaman', 'nama_peminjam', 'id_buku', 'jumlah', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    public $timestamps = true;

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
    // Di model Peminjaman
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }
}
