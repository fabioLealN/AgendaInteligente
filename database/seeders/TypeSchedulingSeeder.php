<?php

namespace Database\Seeders;

use App\Models\TypeScheduling;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSchedulingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typesSchedulings = [
            ['name' => 'Consulta'],
            ['name' => 'Exame'],
            ['name' => 'Cirurgia'],
        ];

        foreach ($typesSchedulings as $typeScheduling) {
            TypeScheduling::create([
                'name' => $typeScheduling['name'],
            ]);
        }
    }
}
