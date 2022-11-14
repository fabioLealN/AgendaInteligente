<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $messages = [
            ['message' => 'Olá! Temos um lembrete para você. Amanhã temos um compromisso marcado. Caso queira cancelar ou reagendar, entre no site!'],
        ];

        foreach ($messages as $message) {
            Message::create([
                'message' => $message['message'],
            ]);
        }
    }
}
