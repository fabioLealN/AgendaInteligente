<?php

namespace App\Jobs;

use App\Models\Distance;
use App\Models\Ong;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class CalculateDistanceOngSide implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ongId;

    public function __construct($ongId)
    {
        $this->ongId = $ongId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ong = Ong::find($this->ongId);
        $ongAddress = $ong->address;
        $ongStreet = $ongAddress->street;
        $ongNumber = $ongAddress->number;
        $ongCity = $ongAddress->city->name;
        $ongState = $ongAddress->city->state->name;

        $URL_BASE = 'https://nominatim.openstreetmap.org/search?';

        $url = $URL_BASE . 'street=' . $ongNumber . ' ' . $ongStreet . '&city=' . $ongCity . '&state=' . $ongState . '&country=brasil&format=json';
        $url = str_replace(' ', '%20', $url);
        $ongLocation = Http::get($url);
        $collectionOngLocation = collect(json_decode($ongLocation));

        $ongCoord = [
            $collectionOngLocation->first()->lat,
            $collectionOngLocation->first()->lon,
        ];

        $users = User::all();

        foreach($users as $user) {
            $userAddress = $user->address;

            $street = $userAddress->street;
            $number = $userAddress->number;
            $city = $userAddress->city->name;
            $state = $userAddress->city->state->name;

            $url = $URL_BASE . 'street=' . $number . ' ' . $street . '&city=' . $city . '&state=' . $state . '&country=brasil&format=json';
            $url = str_replace(' ', '%20', $url);
            $userLocation = Http::get($url);
            $collectionUserLocation = collect(json_decode($userLocation));

            if(!$collectionUserLocation->toArray()) {
                continue;
            }
            Log::info($collectionUserLocation);

            $userCoord = [
                $collectionUserLocation->first()->lat,
                $collectionUserLocation->first()->lon,
            ];

            $distance = collect(GeoFacade::clearResult()
                ->setPoints([
                    $userCoord,
                    $ongCoord
                ])
                ->setOptions(['units' => ['km']])
                ->getDistance())
                ->first();

            if ($distance > 101) {
                Distance::updateOrCreate(
                    ['user_id' => $user->id, 'ong_id' => $ong->id],
                    ['distance' => $distance['km']]
                );
            }
        }
    }
}
