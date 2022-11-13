<?php

namespace App\Http\Controllers;

use App\Services\SchedulingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SchedulingController extends Controller
{
    private $schedulingService;

    public function __construct(SchedulingService $schedulingService)
    {
        $this->schedulingService = $schedulingService;
    }


    public function get(int $id)
    {
        try
        {
            return $this->schedulingService->get($id);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function getAll()
    {
        try
        {
            return $this->schedulingService->getAll();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function getFutureSchedulings()
    {
        try
        {
            return $this->schedulingService->getFutureSchedulings();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'description' => ['string', 'nullable'],
            'pet_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'type_scheduling_id' => ['required', 'integer'],
        ]);

        $schedulingData = $request->only(
            'description',
            'pet_id',
            'schedule_id',
            'type_scheduling_id',
        );

        try
        {
            return $this->schedulingService->store($schedulingData);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => ['string', 'nullable'],
            'schedule_id' => ['required', 'integer'],
        ]);

        $schedulingData = $request->only(
            'description',
            'schedule_id',
        );

        return $this->schedulingService->update($id, $schedulingData);
    }


    public function delete(int $id)
    {
        try
        {
            return $this->schedulingService->delete($id);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }
}
