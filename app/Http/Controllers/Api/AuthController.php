<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Failed to get token.'], 401);
        }

        $user = $request->user();

        // Buat token API untuk user
        $token = $user->createToken('API Token', ['*'], now()->addHour())->plainTextToken;

        return response()->json([
            'message' => 'Token berhasil dibuat.',
            'token' => $token,
        ]);
    }
}
