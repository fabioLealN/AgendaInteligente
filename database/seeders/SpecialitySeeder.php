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
                'icon' => 'vaccines',
                'name' => 'Castração',
                'description' => 'Procedimento cirurgíco em animais para evitar a procriação.'
            ],
            [
                'icon' => 'shower',
                'name' => 'Banho e Tosa',
                'description' => 'Processo de corte de pelos e higienização do animal.'
            ],
            [
                'icon' => 'medical_information',
                'name' => 'Exame',
                'description' => 'Consulta com especialista para avaliar e definar tratamentos.'
            ],
        ];

        foreach ($specialities as $speciality) {
            Speciality::create([
                'icon' => $speciality['icon'],
                'name' => $speciality['name'],
                'description' => $speciality['description'],
            ]);
        }
    }
}
