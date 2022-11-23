<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');
        $response = File::get("database/data/municipios.json");
        collect(json_decode($response))->map(function ($city) {
            City::create([
                'id' => $city->id,
                'name' => $city->nome,
                'state_id' => $city->microrregiao->mesorregiao->UF->id,
            ]);
        });
    }
}
