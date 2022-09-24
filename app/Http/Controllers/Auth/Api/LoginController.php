<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(!auth()->attempt($credentials)) {
            abort(401, 'Email e/ou senha inválidos');
        }

        $token = $request->user()->createToken('auth_token');

        return response()->json(['data' => ['token' => $token->plainTextToken]]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], 204);
    }
}
