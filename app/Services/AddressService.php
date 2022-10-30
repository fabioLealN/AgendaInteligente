<?php

namespace App\Services;

use App\Jobs\CalculateDistanceOngSide;
use App\Jobs\CalculateDistanceUserSide;
use App\Models\Address;
use App\Models\City;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddressService
{
    public function store(array $addressData)
    {
        $city = City::find($addressData['city_id']);
        if (!!!$city) {
            return response()->json(['error' => 'Cidade não encontrada.'], 404);
        }

        try
        {
            return Address::create($addressData);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(User $user, array $addressData)
    {
        $address = Address::find($user->address->id);
        $city = City::find($addressData['city_id']);

        if (!!!$city || !!!$address) {
            return response()->json(['error' => 'Endereço não encontrado.'], 404);
        }

        $address->city = $addressData['city_id'];
        $address->neighborhood = $addressData['neighborhood'];
        $address->street = $addressData['street'];
        $address->number = $addressData['number'];
        $address->cep = $addressData['cep'];

        try
        {
            $address->save();


            if ($user->type_user_id == TypeUser::TYPE_CLIENT) {
                CalculateDistanceUserSide::dispatch($user->id);
            } else {
                CalculateDistanceOngSide::dispatch($user->songs->first()->id);
            }
            return response()->json(['data' => ['status' => 'Atualizado com sucesso!']], 200);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    public function delete(int $id)
    {
        $address = Address::find($id);

        if(!!!$address) {
            throw ValidationException::withMessages(['Endereço não encontrado.']);
        }

        $address->delete();
        return response()->json(['data' => ['status' => 'Excluído com sucesso!']], 200);
    }
}
