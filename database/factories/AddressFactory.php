<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'neighborhood' => fake()->name(),
            'street' => fake()->name(),
            'city_id' => City::factory(),
            'number' => fake()->randomNumber(2),
            'cep' => fake()->randomNumber(2),
        ];
    }
}
