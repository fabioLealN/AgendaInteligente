<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $url = 'https://servicodados.ibge.gov.br/api/v1/localidades';
        $estados = Http::get($url.'/estados');

        foreach ($estados->json() as $value) {
            print("Criando estado {$value['nome']}");
            $state = State::forceCreate([
                'id' => $value['id'],
                'name' => $value['nome'],
            ]);

            $cidades = Http::get($url."/estados/{$state->id}/distritos");
            foreach ($cidades->json() as $cidade) {
                print("Criando cidade {$cidade['nome']}\n");
                $city = City::forceCreate([
                    'id' => $cidade['id'],
                    'name' => $cidade['nome'],
                    'state_id' => $state->id,
                ]);
            }
        }
    }
}
