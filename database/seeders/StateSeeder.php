<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');

        $states = collect(json_decode($response))->map(function ($state) {
            return [
                'id' => $state->id,
                'name' => $state->nome,
                'abbreviation' => $state->sigla,
            ];
        })->toArray();

        foreach ($states as $state) {
            print($state['id']);
            State::create([
                'id' => $state['id'],
                'name' => $state['name'],
                'abbreviation' => $state['abbreviation'],
            ]);
        }
    }
}
