<?php

namespace Database\Seeders;

use App\Models\Ong;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserOngSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeOngId = TypeUser::where('name', 'ONG')->first()->id;

        $users = User::where('type_user_id', $typeOngId)->get();

        $users->each(function ($user) {
            $user->ongs()->attach(Ong::all()->random()->id);
        });
    }
}
