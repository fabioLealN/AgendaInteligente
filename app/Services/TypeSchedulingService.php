<?php

namespace App\Services;

use App\Models\TypeScheduling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TypeSchedulingService
{
    public function get(int $id)
    {
        $typeScheduling = TypeScheduling::find($id);

        if(!!!$typeScheduling) {
            throw ValidationException::withMessages(['Tipo de agendamento não encontrado.']);
        }

        return $typeScheduling;
    }

    public function getAll()
    {
        $typesSchedulings = TypeScheduling::all();

        if(!!!$typesSchedulings) {
            throw ValidationException::withMessages(['Não há tipos de agendamentos salvos.']);
        }

        return $typesSchedulings;
    }

    public function store(array $typeSchedulingData)
    {
        try
        {
            DB::beginTransaction();
                $typeScheduling = TypeScheduling::create($typeSchedulingData);
            DB::commit();

            return response()->json(['data' => ['type_scheduling' => $typeScheduling]], 201);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            throw ValidationException::withMessages(['Ocorreu um erro durante a criação do tipo de agendamento.']);
        }
    }

    public function update(int $id, Request $request)
    {
        $typeScheduling = TypeScheduling::find($id);

        if(!!!$typeScheduling) {
            return response()->json(['error' => 'Tipo de agendamento não encontrado.'], 404);
        }

        try
        {
            DB::beginTransaction();
                $typeScheduling->name = $request->input('name');
                $typeScheduling->save();
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
