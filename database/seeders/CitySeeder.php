<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

        $cities = collect(json_decode($response))->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->nome,
                'state_id' => $city->microrregiao->mesorregiao->UF->id,
            ];
        })->toArray();

        foreach ($cities as $city) {
            City::create([
                'id' => $city['id'],
                'name' => $city['name'],
                'state_id' => $city['state_id'],
            ]);
        }
    }
}
