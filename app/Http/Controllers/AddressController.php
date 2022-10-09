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

    public function store($addressData) {
        return $this->addressService->store($addressData);
    }

    public function update(Request $request) {
        $request->validate([
            'city_id' => ['required'],
            'neighborhood' => ['required'],
            'street' => ['required'],
            'number' => ['required'],
            'cep' => ['required'],
        ]);

        $user = User::find($request->user()->id);
        $addressData = $request->only('city_id', 'neighborhood', 'street', 'number', 'cep');

        return $this->addressService->store($user, $addressData);
    }
}
