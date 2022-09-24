<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function store($addressData) {
        return Address::create([
            'city_id' => $addressData['city_id'],
            'neighborhood' => $addressData['neighborhood'],
            'street' => $addressData['street'],
            'number' => $addressData['number'],
            'cep' => $addressData['cep'],
        ]);
    }
}
