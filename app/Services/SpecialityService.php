<?php

namespace App\Services;

use App\Models\Ong;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SpecialityService
{
    public function get(int $id)
    {
        $speciality = Speciality::find($id);

        if(!!!$speciality) {
            throw ValidationException::withMessages(['Especialidade não encontrada.']);
        }

        return response()->json(['data' => $speciality]);
    }

    public function getAll()
    {
        $specialities = Speciality::all();

        if(!!!$specialities) {
            throw ValidationException::withMessages(['Não há especialidades salvas.']);
        }

        return response()->json(['data' => $specialities]);
    }

    public function getOngs($specialityId)
    {
        $ongs = Ong::with('specialities')
            ->whereRelation('specialities', 'specialities.id', $specialityId)
            ->with('distances')
            ->whereRelation('distances', 'user_id', Auth::user()->id)
            ->with('address')
            ->get();

        if (!!!$ongs->first()) {
            $ongs = Ong::with('specialities')
            ->whereRelation('specialities', 'specialities.id', $specialityId)
            ->with('address')
            ->whereRelation('address', 'city_id', Auth::user()->address->city_id)
            ->get();
        }

        if (!!!$ongs->first()) {
            return response()->json(['error' => 'Nenhuma ONG próxima encontrada.'], 404);
        }

        return response()->json(['data' => $ongs]);
    }


    public function store(array $specialityData)
    {
        try
        {
            DB::beginTransaction();
                $speciality = Speciality::create($specialityData);
            DB::commit();

            return response()->json(['data' => ['speciality' => $speciality]], 201);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            throw ValidationException::withMessages(['Ocorreu um erro durante a criação da especialidade.']);
        }
    }

    public function update(int $id, Request $request)
    {
        $speciality = Speciality::find($id);

        if(!!!$speciality) {
            return response()->json(['error' => 'Especialidade não encontrada.'], 404);
        }

        try
        {
            DB::beginTransaction();
                $speciality->name = $request->input('name');
                $speciality->description = $request->input('description');
                $speciality->save();
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
