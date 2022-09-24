<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $user->type_user_id = $request->input('type_user_id');

        $user->save();

        return response()->json([
            'status' => 'Atualizado com sucesso!',
        ]);
    }
}
