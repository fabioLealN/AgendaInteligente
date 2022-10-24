<?php

namespace Database\Seeders;

use App\Models\TypeUser;
use Illuminate\Database\Seeder;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typesUsers = [
            [
                'id' => 1,
                'name' => 'ONG'
            ],
            [
                'id' => 2,
                'name' => 'Cliente'
            ],
        ];

        foreach ($typesUsers as $typeUser) {
            TypeUser::create([
                'id' => $typeUser['id'],
                'name' => $typeUser['name'],
            ]);
        }
    }
}
