<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-7 days', '+7 days')->getTimestamp());
        $start = $date->format('H:i');
        $end = $date->addHour()->format('H:i');

        return [
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'interval_time' => $end,
            'available' => true,
        ];
    }
}
