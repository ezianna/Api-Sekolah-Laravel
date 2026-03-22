<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Ambil credentials
        $credentials = $request->only('email', 'password');

        // Cek login dan generate token
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Email atau Password salah'], 401);
        }

        // Jika sukses, kembalikan token
        return response()->json([
            'success' => true,
            'user'    => auth('api')->user(),
            'token'   => $token
        ], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}