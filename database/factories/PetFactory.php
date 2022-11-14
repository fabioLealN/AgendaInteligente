<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake('pt_BR')->name(),
            'birth_date' => fake('pt_BR')->date(),
            'user_id' => User::all()->random()->id,
            'breed_id' => Breed::all()->random()->id,
            'size_id' => Size::all()->random()->id,
        ];
    }
}
