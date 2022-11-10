<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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


    public function getAllAvailable(Request $request)
    {
        $request->validate(['ong_id' => ['required']]);

        try
        {
            return response()->json(['data' => $this->scheduleService->getAllAvailable($request->input('ong_id'))]);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'duration' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'days_week' => ['array', 'nullable'],
            'users_ids' => ['required', 'array'],
        ]);

        $scheduleData = $request->only(
            'start_date',
            'end_date',
            'duration',
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'available' => ['required', 'boolean'],
        ]);

        return $this->scheduleService->update($id, $request);
    }
}
