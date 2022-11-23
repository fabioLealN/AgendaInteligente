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
        $user =  $request->user()->load(['pets','pets.breed', 'pets.size', 'ongs']);

        return response()->json(['data' => ['token' => $token->plainTextToken, 'user' => $user]]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], 204);
    }
}
