<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PetService;
use Illuminate\Validation\ValidationException;

class PetController extends Controller
{
    private $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function getAll()
    {
        try
        {
            return $this->petService->getAll();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function get($id)
    {

        return response()->json(['data' => $this->petService->get($id)]);

    }

    public function store(Request $request)
    {
        $this->validateData($request);

        $petData = $request->only('name', 'birth_date', 'breed_id', 'size_id');

        return $this->petService->store($petData);
    }

    public function update(Request $request, $id)
    {
        $this->validateData($request);

        return $this->petService->update($id, $request);
    }

    private function validateData(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'breed_id' => ['required', 'integer'],
            'size_id' => ['required', 'integer'],
        ]);
    }
}
