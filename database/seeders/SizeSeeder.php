<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [
            ['name' => 'Pequeno Porte'],
            ['name' => 'Médio Porte'],
            ['name' => 'Grande Porte']
        ];

        foreach ($sizes as $size) {
            Size::create([
                'name' => $size['name'],
            ]);
        }
    }
}
