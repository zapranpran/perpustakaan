<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'id_user',
        'id_peminjaman',
        'tanggal_penggembalian',
        'denda',
        'status_kembali',
        'alasan_kembali'

    ];
    public $timestamps = true;

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
