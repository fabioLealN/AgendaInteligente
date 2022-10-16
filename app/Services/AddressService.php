<?php

namespace App\Services;

use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddressService
{
    public function store(array $addressData)
    {
        $city = City::find($addressData['city_id']);
        if (!!!$city) {
            return response()->json(['error' => 'Ocorreu um erro! Tente novamente mais tarde.'], 404);
        }

        try
        {
            return Address::create($addressData);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    public function update(User $user, array $addressData)
    {
        $address = Address::find($user->address->id);
        $city = City::find($addressData['city_id']);

        if (!!!$city || !!!$address) {
            return response()->json(['error' => 'Ocorreu um erro! Tente novamente mais tarde.'], 404);
        }

        $address->city = $addressData['city_id'];
        $address->neighborhood = $addressData['neighborhood'];
        $address->street = $addressData['street'];
        $address->number = $addressData['number'];
        $address->cep = $addressData['cep'];

        try
        {
            $address->save();
            return response()->json(['status' => 'Atualizado com sucesso!', 200]);
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
            throw new ValidationException('Endereço não encontrado.', 422);
        }

        $address->delete();
        return response()->json(['status' => 'Excluído com sucesso!', 200]);
    }
}
