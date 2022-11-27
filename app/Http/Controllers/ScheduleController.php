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
            'final_date' => ['required', 'date'],
            'duration' => ['required'],
            'start_time' => ['required'],
            'final_time' => ['required'],
            'days_week' => ['array', 'nullable'],
            'specialists_ids' => ['required', 'array'],
            'sizes_ids' => ['required', 'array'],
        ]);

        $scheduleData = $request->only(
            'start_date',
            'final_date',
            'duration',
            'start_time',
            'final_time',
            'days_week',
            'specialists_ids',
            'sizes_ids'
        );

        try
        {
            return response()->json(['data' => $this->scheduleService->store($scheduleData)]);
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
            'final_time' => ['required'],
            'available' => ['required', 'boolean'],
            'sizes_ids' => ['required', 'array']
        ]);

        return $this->scheduleService->update($id, $request);
    }
}
