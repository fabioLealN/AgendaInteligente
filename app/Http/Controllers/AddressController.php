<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function store(Request $request) {
        $this->validateData($request);

        $addressData = $request->only('city_id', 'neighborhood', 'street', 'number', 'cep');

        return $this->addressService->store($addressData);
    }

    public function update(Request $request) {
        $this->validateData($request);

        $user = User::find($request->user()->id);
        $addressData = $request->only('city_id', 'neighborhood', 'street', 'number', 'cep');

        return $this->addressService->update($user, $addressData);
    }

    public function delete(int $id)
    {
        return $this->addressService->delete($id);
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'city_id' => ['required', 'integer'],
            'neighborhood' => ['required'],
            'street' => ['required'],
            'number' => ['required'],
            'cep' => ['required'],
        ]);
    }
}
