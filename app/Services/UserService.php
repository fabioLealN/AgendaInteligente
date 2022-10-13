<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function store(array $userData)
    {
        $userData['password'] = bcrypt($userData['password']);

        try  {
            $user = User::create($userData);
            return response()->json(['data' => ['user' => $user]], 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    public function update(Request $request)
    {
        $user = User::find($request->user()->id);
        if (!!!$user) {
            return response()->json(['error' => 'Ocorreu um erro! Tente novamente mais tarde.'], 404);
        }

        try
        {
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->type_user_id = $request->input('type_user_id');
            $user->save();
            return response()->json(['status' => 'Atualizado com sucesso!', 200]);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }
}
