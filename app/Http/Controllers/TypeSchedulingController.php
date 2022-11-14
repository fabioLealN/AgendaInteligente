<?php

namespace App\Http\Controllers;

use App\Services\TypeSchedulingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TypeSchedulingController extends Controller
{
    private $typeSchedulingService;

    public function __construct(TypeSchedulingService $typeSchedulingService)
    {
        $this->typeSchedulingService = $typeSchedulingService;
    }

    public function get($id)
    {
        try
        {
            return $this->typeSchedulingService->get($id);
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
            return $this->typeSchedulingService->getAll();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        try
        {
            $typeSchedulingData = $request->only('name');

            return $this->typeSchedulingService->store($typeSchedulingData);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => ['required', 'string']
        ]);

        return $this->typeSchedulingService->update($id, $request);
    }
}
