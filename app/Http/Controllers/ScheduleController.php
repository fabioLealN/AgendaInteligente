<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'interval' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'days_week' => ['string', 'nullable'],
            'users_ids' => ['required', 'integer'],
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
