<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Ong;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OngSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ongs = [
            ['name' => 'ONDA'],
            ['name' => 'Bichinho Feliz'],
        ];

        foreach ($ongs as $ong) {
            Ong::create([
                'name' => $ong['name'],
                'address_id' => Address::all()->random()->id,
            ]);
        }
    }
}
