<?php

namespace App\Services;

use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BreedService
{
    public function get(int $id)
    {
        $breed = Breed::find($id);

        if(!!!$breed) {
            throw ValidationException::withMessages(['Raça não encontrada.']);
        }

        return $breed;
    }

    public function getAll()
    {
        $breeds = Breed::all();

        if(!!!$breeds) {
            throw ValidationException::withMessages(['Não há raças salvas.']);
        }

        return $breeds;
    }

    public function store(array $breedData)
    {
        try
        {
            DB::beginTransaction();
                $breed = Breed::create($breedData);
            DB::commit();

            return response()->json(['data' => ['breed' => $breed]], 201);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            throw ValidationException::withMessages(['Ocorreu um erro durante a criação da raça.']);
        }
    }

    public function update(int $id, Request $request)
    {
        $breed = Breed::find($id);

        if(!!!$breed) {
            return response()->json(['error' => 'Raça não encontrada.'], 404);
        }

        try
        {
            DB::beginTransaction();
                $breed->name = $request->input('name');
                $breed->save();
            DB::commit();

            return response()->json(['data' => ['status' => 'Atualizado com sucesso!']], 200);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
