<?php

namespace App\Services;

use App\Jobs\CalculateDistanceUserSide;
use App\Models\Ong;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function get($id)
    {
        $user = User::find($id);

        if(!$user) {
            throw ValidationException::withMessages(['Usuário não encontrado.']);
        }

        return $user;
    }

    public function store(array $userData, ?array $ongsIds, ?array $specialitiesIds)
    {
        $userData['password'] = bcrypt($userData['password']);
        $specialities = new Collection();
        $ongs = new Collection();

        try  {
            if (!is_null($ongsIds) && !is_null($specialitiesIds)) {
                foreach ($ongsIds as $ongId) {
                    $ongs->push(Ong::findOrFail($ongId));
                }

                foreach ($specialitiesIds as $specialityId) {
                    $specialities->push(Speciality::findOrFail($specialityId));
                }
            }

            $user = User::create($userData);
            $user->specialities()->attach($specialitiesIds);
            $user->ongs()->attach($ongsIds);
            $ongs->each(
                fn ($ong) => $ong->specialists()->syncWithoutDetaching(
                    $user->specialities->pluck('pivot')->pluck('id')
                ));
            $specialities->each(fn ($speciality) => $speciality->ongs()->syncWithoutDetaching($ongsIds));

            // CalculateDistanceUserSide::dispatch($user->id);

            return response()->json(['data' => ['user' => $user]], 201);

        } catch (ValidationException | ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request)
    {
        $user = User::find(29);//$request->user()->id);
        if (!!!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        try
        {
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->type_user_id = $request->input('type_user_id');
            $user->specialities()->sync($request->input('specialities_ids'));
            $user->ongs()->sync($request->input('ongs_ids'));
            $user->ongs->each(
                fn ($ong) => $ong->specialists()->syncWithoutDetaching(
                    $user->specialities->pluck('pivot')->pluck('id')
                ));
            $user->save();

            return response()->json(['data' => ['status' => 'Atualizado com sucesso!']], 200);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }
}
