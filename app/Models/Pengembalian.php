<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $fillable = ['id_user', 'id_peminjaman', 'tanggal_pengembalian', 'status', 'alasan_kembali'];
    public $timestamps = true;

    // Di model Pengembalian
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
