<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\User;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Tests\TestCase;
use Faker\Factory as Faker;

class ScheduleServiceUnitTest extends TestCase
{
    protected ScheduleService $scheduleService;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->scheduleService = $this->app->make(ScheduleService::class);
    }

    public function test_store_schedule()
    {
        $data = [
            'start_date' => Carbon::createFromDate(2022, 11, 21),
            'final_date' => Carbon::createFromDate(2022, 11, 26),
            'duration' => 30,
            'start_time' => '09:00',
            'final_time' => '15:00',
            'specialists_ids' => User::where('type_user_id', 1)->get()->pluck('id'),
        ];
        $response = $this->scheduleService->store($data);
        $this->assertInstanceOf(Schedule::class, $response[0]);
    }
}
