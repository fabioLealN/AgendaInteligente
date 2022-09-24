<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request, User $user, AddressController $addressController)
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
            'type_user_id' => ['required'],
            'city_id' => ['required'],
            'neighborhood' => ['required'],
            'street' => ['required'],
            'number' => ['required'],
            'cep' => ['required'],
        ]);

        $address = $addressController->store($request->only('city_id', 'neighborhood', 'street', 'number', 'cep'));

        $userData = $request->only('name', 'email', 'password', 'phone', 'type_user_id');
        $userData['password'] = bcrypt($userData['password']);
        $userData['address_id'] = $address->id;

        if(!$user = $user->create($userData)) {
            abort(500, 'Ocorreu um erro! Tente novamente mais tarde.');
        }

        return response()->json(['data' => ['user' => $user]]);
    }
}
