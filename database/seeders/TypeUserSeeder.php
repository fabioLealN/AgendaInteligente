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
            ['name' => 'ONG'],
            ['name' => 'Cliente'],
        ];

        foreach ($typesUsers as $typeUser) {
            TypeUser::create([
                'name' => $typeUser['name'],
            ]);
        }
    }
}
