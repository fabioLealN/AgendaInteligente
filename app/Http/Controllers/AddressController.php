<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    private $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function get($id)
    {
        try
        {
            return $this->addressService->get($id);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request) {
        $this->validateData($request);

        $addressData = $request->only('city_id', 'neighborhood', 'street', 'number', 'cep');

        return $this->addressService->store($addressData);
    }

    public function update(Request $request, $id) {
        $this->validateData($request);

        $addressData = $request->only('city_id', 'neighborhood', 'street', 'number', 'cep');

        return $this->addressService->update($id, $addressData);
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
