<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO: Definir espécies para o BD
        $breeds = [
            ['name' => 'Cão'],
            ['name' => 'Gato'],
            ['name' => 'Ave'],
        ];

        foreach ($breeds as $breed) {
            Breed::create([
                'name' => $breed['name'],
            ]);
        }
    }
}
