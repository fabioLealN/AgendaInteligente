<?php

namespace Database\Seeders;

use App\Models\Speciality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialities = [
            [
                'name' => 'Castração',
                'description' => 'Procedimento cirurgíco em animais para evitar a procriação.'
            ],
            [
                'name' => 'Banho e Tosa',
                'description' => 'Processo de corte de pelos e higienização do animal.'
            ],
            [
                'name' => 'Exame',
                'description' => 'Consulta com especialista para avaliar e definar tratamentos.'
            ],
        ];

        foreach ($specialities as $speciality) {
            Speciality::create([
                'name' => $speciality['name'],
                'description' => $speciality['description'],
            ]);
        }
    }
}
