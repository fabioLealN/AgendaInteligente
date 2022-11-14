<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Ong;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => "Admin",
            'phone' => fake('pt_BR')->phoneNumber(),
            'email' => "admin@admin.com",
            'password' => bcrypt("123456"),
            'address_id' => Address::all()->random()->id,
            'type_user_id' => TypeUser::all()->random()->id
        ]);

        $admin->ongs()->attach(Ong::first());

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => fake('pt_BR')->name(),
                'phone' => fake('pt_BR')->phoneNumber(),
                'email' => fake('pt_BR')->email(),
                'password' => bcrypt(fake()->password()),
                'address_id' => Address::all()->random()->id,
                'type_user_id' => TypeUser::all()->random()->id
            ]);
        }
    }
}
