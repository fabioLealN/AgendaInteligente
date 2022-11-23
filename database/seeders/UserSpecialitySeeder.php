<?php

namespace Database\Seeders;

use App\Models\Speciality;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('type_user_id', 1)->get()->each(fn($u) => $u->specialities()->attach(Speciality::all()->pluck('id')));
    }
}
