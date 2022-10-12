<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\Schedule;
use App\Models\Scheduling;
use App\Models\TypeScheduling;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SchedulingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedule = Schedule::first();
        $schedule->available = false;
        $schedule->save();

        $schedulings = [
            'description' => fake('pt_BR')->text(),
            'pet_id' => Pet::first()->id,
            'schedule_id' => $schedule->id,
            'type_scheduling_id' => TypeScheduling::first()->id,
        ];

        Scheduling::create($schedulings);
    }
}
