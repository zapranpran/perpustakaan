<?php

use App\Http\Controllers\Api\ApiBukuController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Models\Buku;
use App\Models\peminjaman;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/buku', ApiBukuController::class)->except('create', 'edit');
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'indexapi']);
    Route::resource('/profile', ProfileController::class)->except('create', 'edit');

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});

Route::get('/get-peminjaman/{no_peminjaman}', function ($no_peminjaman) {
    $peminjaman = peminjaman::where('nomor_peminjaman', $no_peminjaman)->first();

    if (!$peminjaman) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    $buku_dipinjam = Buku::whereIn('id', explode(',', $peminjaman->id_buku))->get();

    return response()->json([
        'nama_peminjam' => $peminjaman->nama_peminjam,
        'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
        'batas_pinjam' => $peminjaman->batas_pinjam,
        'buku_dipinjam' => $buku_dipinjam->map(function ($buku) {
            return [
                'id_buku' => $buku->id,
                'judul' => $buku->judul,
                'jumlah' => $buku->jumlah
            ];
        }),
    ]);
});
