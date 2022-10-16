<?php

namespace App\Services;

use App\Models\Ong;
use App\Models\Speciality;
use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OngService
{
    public function get($id)
    {
        $ong = Ong::find($id);

        if(!$ong) {
            throw ValidationException::withMessages(['ONG não encontrada']);
        }

        return $ong->specialities;
    }

    public function getAll()
    {
        $ongs = Ong::all();

        if(!$ongs) {
            throw ValidationException::withMessages(['Não há ONGs salvas.']);
        }

        return $ongs->each(fn($ong) => $ong->specialities);
    }

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
        catch (BadMethodCallException | ValidationException $e)
        {
            DB::rollBack();
            throw ValidationException::withMessages([$e->getMessage()]);
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
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
