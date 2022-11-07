<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeederProduction extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StateSeeder::class,
            CitySeeder::class,
            TypeUserSeeder::class,
            MessageSeeder::class,
            BreedSeeder::class,
            SizeSeeder::class,
            SpecialitySeeder::class,
            TypeSchedulingSeeder::class,
        ]);

        return response()->json(['status' => 'Seed concluído.'], 200);
    }
}
