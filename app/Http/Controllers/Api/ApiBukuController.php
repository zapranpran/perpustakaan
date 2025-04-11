<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Http\Request;
use App\Models\Buku;

class ApiBukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('penulis', 'penerbit', 'kategori')->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Buku',
            'bukus' => $buku,
        ];
        return response()->json($res,200);
    }

    public function show($id){
        try {
            $buku = Buku::with('penulis', 'penerbit', 'kategori')->findOrFail($id);
            $res = [
                'success' => true,
                'message' => 'Detail Buku',
                'bukus' => $buku,
            ];
            return response()->json($res,200);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Data tidak ada',
                'error' => $e->getMessage(),
            ];
            return response()->json($res,404);
        }

    }
}
