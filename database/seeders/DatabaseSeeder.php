<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            ScheduleSeeder::class,

            // Dependem de outros seeders
            AddressesSeeder::class,
            OngSeeder::class,
            UserSeeder::class,
            PetSeeder::class,
            SchedulingSeeder::class,


            // Relações many to many
            OngSpecialitySeeder::class,
            UserOngSeeder::class,
            // UserScheduleSeeder::class,
            UserSpecialitySeeder::class
        ]);
    }
}
