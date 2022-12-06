<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendScheduleReminder extends Command
{
    protected $signature = 'send:schedule_reminder';


    protected $description = 'Sends a message to the customer to remind the appointment the next day.';


    public function handle()
    {
        $idWhatsapp = env('ID_WHATSAPP');
        $tokenWhatsapp = env('TOKEN_WHATSAPP');
        $templateWhatsapp = env('TEMPLATE_WHATSAPP');

        $tomorrow = Carbon::today('America/Sao_Paulo')->addDay()->format('Y-m-d');

        $schedules_teste = Schedule::with('schedulings.pet.user')
            ->where('available', false)
            ->where('date', $tomorrow)
            ->get();

        $schedules_teste->each(function ($item) use ($idWhatsapp, $tokenWhatsapp, $templateWhatsapp) {
            $phone = $item->schedulings->pet->user->phone;
            $phone = '55' . str_replace([' ', '-', '(', ')'], '', $phone);

            $post = [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'template',
                'template' => [
                    'name' => $templateWhatsapp,
                    'language' => [
                        'code' => 'pt_BR',
                    ]
                ]
            ];

            $ch = curl_init("https://graph.facebook.com/v15.0/{$idWhatsapp}/messages");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer ' . $tokenWhatsapp));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post));
            curl_exec($ch);
            curl_close($ch);
        });
    }
}
