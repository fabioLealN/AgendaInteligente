<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [];

        for ($weight = 0; $weight <= 50; $weight+=5) {
            $first = $weight;

            if ($weight >= 5) $first += 1;

            if ($weight === 50) {
                $size = "+{$weight}Kg";
            } else {
                $second = $weight + 5;
                $size = "{$first}Kg - {$second}Kg";
            }

            array_push($sizes, ['name' => $size]);
        }

        Size::insert($sizes);
    }
}
