<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PetService;

class PetController extends Controller
{
    private $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function getAll()
    {
        return Pet::where('user_id', Auth::user()->id)->get();
    }

    public function get($id)
    {
        return Pet::where('id', $id)
                    ->where('user_id', Auth::user()->id)->get();
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

        $this->petService->update($id, $request);
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
