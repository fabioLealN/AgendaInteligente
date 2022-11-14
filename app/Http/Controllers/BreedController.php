<?php

namespace App\Http\Controllers;

use App\Services\BreedService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BreedController extends Controller
{
    private $breedService;

    public function __construct(BreedService $breedService)
    {
        $this->breedService = $breedService;
    }

    public function get($id)
    {
        return response()->json(['data' => $this->breedService->get($id)]);

    }

    public function getAll()
    {

        return response()->json(['data' => $this->breedService->getAll()]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        try
        {
            $breedData = $request->only('name');

            return $this->breedService->store($breedData);
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

        return $this->breedService->update($id, $request);
    }
}
