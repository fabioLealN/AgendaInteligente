<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'state' => ['required'],
            'city' => ['required'],
            'district' => ['required'],
            'street' => ['required'],
            'number' => ['required'],
            'cep' => ['required'],
        ]);

        $adressDate = $request->only('state', 'city', 'district', 'street', 'number', 'cep');

        //TODO: envia endereço para registrar e retorna o id para salvar na tabela usuário
        //TODO: falta salvar o id do tipo de usuário tbm

        $userData = $request->only('name', 'email', 'password', 'phone');
        $userData['password'] = bcrypt($userData['password']);

        if(!$user = $user->create($userData)) {
            abort(500, 'Ocorreu um erro! Tente novamente mais tarde.');
        }

        return response()->json(['data' => ['user' => $user]]);
    }
}
