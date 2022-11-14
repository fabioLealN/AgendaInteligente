<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\City;
use Illuminate\Database\Seeder;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::all()->each(function ($city) {
            Address::create([
                'city_id' => $city->id,
                'neighborhood' => fake('pt_BR')->streetName(),
                'street' => fake('pt_BR')->streetName(),
                'number' => fake('pt_BR')->buildingNumber(),
                'cep' => fake('pt_BR')->postcode(),
            ]);
        });
    }
}
