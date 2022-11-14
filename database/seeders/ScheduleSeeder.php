<?php

namespace Database\Seeders;

use App\Models\Ong;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedules = [
            [
                'date' => Carbon::createFromDate(2022, 10, 17),
                'start_time' => Carbon::create(2022, 10, 17, 8, 0, 0),
                'end_time' => Carbon::create(2022, 10, 17, 8, 00, 0)->addMinutes(60),
                'available' => true,
            ],
            [
                'date' => Carbon::createFromDate(2022, 10, 17),
                'start_time' => Carbon::create(2022, 10, 17, 9, 10, 0),
                'end_time' => Carbon::create(2022, 10, 17, 9, 10, 0)->addMinutes(60),
                'available' => true,
            ],
            [
                'date' => Carbon::createFromDate(2022, 10, 17),
                'start_time' => Carbon::create(2022, 10, 17, 10, 20, 0),
                'end_time' => Carbon::create(2022, 10, 17, 10, 20, 0)->addMinutes(60),
                'available' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
