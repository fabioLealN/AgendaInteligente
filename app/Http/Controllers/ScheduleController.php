<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Ong;
use App\Models\Schedule;
use App\Models\State;
use App\Models\User;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class ScheduleController extends Controller
{
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function get(int $id)
    {
        try
        {
            return $this->scheduleService->get($id);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function getByDistance()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $userAddress = $user->address;

        $street = $userAddress->street;
        $number = $userAddress->number;
        $city = $userAddress->city->name;
        $state = $userAddress->city->state->name;

        $URL_BASE = 'https://nominatim.openstreetmap.org/search?';

        $url = $URL_BASE . 'street=' . $number . ' ' . $street . '&city=' . $city . '&state=' . $state . '&country=brasil&format=json';
        $url = str_replace(' ', '%20', $url);
        $response = Http::get($url);

        $userLat = $response[0]['lat'];
        $userLon = $response[0]['lon'];


        $ongs = Ong::all();
        $arrayResponse = [];

        foreach($ongs as $ong) {
            $ongAddress = $ong->address;

            $ongStreet = $ongAddress->street;
            $ongNumber = $ongAddress->number;
            $ongCity = $ongAddress->city->name;
            $ongState = $ongAddress->city->state->name;

            $url = $URL_BASE . 'street=' . $ongNumber . ' ' . $ongStreet . '&city=' . $ongCity . '&state=' . $ongState . '&country=brasil&format=json';
            $url = str_replace(' ', '%20', $url);
            $ongResponse = Http::get($url);
            $coll = collect(json_decode($ongResponse));

            if(!$coll->toArray()) {
                continue;
            }

            $ongLat = $ongResponse[0]['lat'];
            $ongLon = $ongResponse[0]['lon'];


            $isInArea = GeoFacade::setMainPoint([$userLat, $userLon])
                ->setDiameter(1000)
                ->setPoint([$ongLat, $ongLon])
                ->isInArea();

            array_push($arrayResponse, $isInArea);
        }


        return $arrayResponse;
    }


    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'interval' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'days_week' => ['array', 'nullable'],
            'users_ids' => ['required', 'array'],
        ]);

        $scheduleData = $request->only(
            'start_date',
            'end_date',
            'interval',
            'start_time',
            'end_time',
            'days_week',
            'users_ids'
        );

        try
        {
            return $this->scheduleService->store($scheduleData);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
