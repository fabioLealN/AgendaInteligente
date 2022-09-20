<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function updateUser(Request $request) {
        $request->validate([
            'name' => ['required'],
            'phone' => ['required'],
        ]);

        $user = User::find($request->user()->id);
        if (!!!$user) {
            return response()->json(['status' => 'Ocorreu um erro! Tente novamente mais tarde.']);
        }

        $user->name = $request->input('name');
        $user->phone = $request->input('phone');

        $user->save();

        return response()->json([
            'status' => 'Atualizado com sucesso!',
        ]);
    }
}
