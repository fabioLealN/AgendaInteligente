<?php

namespace Database\Seeders;

use App\Models\Ong;
use App\Models\Speciality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OngSpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ongs = Ong::all();

        $ongs->each(function ($ong) {
            $ong->specialities()->attach(Speciality::all()->random()->id);
        });
    }
}
