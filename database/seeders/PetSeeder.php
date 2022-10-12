<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 15; $i++) {
            Pet::create([
                'name' => fake('pt_BR')->name(),
                'birth_date' => fake('pt_BR')->date(),
                'user_id' => User::all()->random()->id,
                'breed_id' => Breed::all()->random()->id,
                'size_id' => Size::all()->random()->id,
            ]);
        }
    }
}
