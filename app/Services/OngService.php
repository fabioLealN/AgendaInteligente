<?php

namespace App\Services;

use App\Models\Ong;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OngService
{
    public function store(array $ongData)
    {
        $specialities = Speciality::whereIn('id', $ongData['specialities_ids'])->get();

        if(!!!$specialities) {
            return response()->json(['error' => 'Especialidade(s) não encontrada(s).'], 404);
        }

        try  {
            DB::beginTransaction();
                $ong = Ong::create($ongData);
                $ong->specialities()->attach($ongData['specialities_ids']);
            DB::commit();

            return response()->json(['data' => ['ong' => $ong]], 201);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            throw new ValidationException($e->getMessage(), 422);
        }
    }

    public function update(int $id, Request $request)
    {
        $ong = Ong::find($id);

        if(!!!$ong) {
            return response()->json(['error' => 'ONG ou Especialidade(s) não encontrada(s).'], 404);
        }

        try  {
            DB::beginTransaction();
                $ong->name = $request->input('name');
                $ong->specialities()->detach();
                $ong->specialities()->attach($request->input('specialities_ids'));
                $ong->save();
            DB::commit();

            return response()->json(['status' => 'Atualizado com sucesso!'], 200);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }
}
