<?php

namespace App\Services;

use App\Jobs\CalculateDistanceOngSide;
use App\Jobs\CalculateDistanceUserSide;
use App\Models\Address;
use App\Models\City;
use App\Models\Ong;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddressService
{
    public function get(int $id)
    {
        $Address = Address::find($id);

        if(!!!$Address) {
            throw ValidationException::withMessages(['Endereço não encontrado.']);
        }

        return $Address;
    }

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

    public function update(int $id, array $addressData)
    {
        $address = Address::find($id);
        $city = City::find($addressData['city_id']);

        if (!!!$city || !!!$address) {
            return response()->json(['error' => 'Endereço não encontrado.'], 404);
        }

        try
        {
            $address->city_id = $addressData['city_id'];
            $address->neighborhood = $addressData['neighborhood'];
            $address->street = $addressData['street'];
            $address->number = $addressData['number'];
            $address->cep = $addressData['cep'];
            $address->save();

            $addressBelongsToUser = User::where('address_id',  $id)->first();

            if (!!$addressBelongsToUser) {
                CalculateDistanceUserSide::dispatch($addressBelongsToUser->id);
            } else {
                $addressBelongsToOng = Ong::where('address_id',  $id)->first();
                CalculateDistanceOngSide::dispatch($addressBelongsToOng->id);
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
