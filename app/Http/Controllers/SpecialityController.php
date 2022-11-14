<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use App\Services\SpecialityService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SpecialityController extends Controller
{
    private $specialityService;

    public function __construct(SpecialityService $specialityService)
    {
        $this->specialityService = $specialityService;
    }

    public function get(int $id)
    {
        try
        {
            return $this->specialityService->get($id);
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
            return $this->specialityService->getAll();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getOngs(Speciality $id)
    {
        return $this->specialityService->getOngs($id->id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        try
        {
            $specialityData = $request->only('name', 'description');

            return $this->specialityService->store($specialityData);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        return $this->specialityService->update($id, $request);
    }
}
