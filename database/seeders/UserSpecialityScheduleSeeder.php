<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSpecialityScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $ongUser = User::where('type_user_id', TypeUser::where('name', 'ONG')->first()->id)->first();

        // $schedules = Schedule::all()->pluck('id');

        // $ongUser->schedules()->attach($schedules);
    }
}
