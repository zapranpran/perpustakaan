<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json([
            'success' => true,
            'message' => 'All Data User',
            'profiles' => $user,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data User',
            'profiles' => $user,
        ]);
    }
}
