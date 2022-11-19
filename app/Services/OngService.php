<?php

namespace App\Services;

use App\Jobs\CalculateDistanceOngSide;
use App\Models\Ong;
use App\Models\Speciality;
use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OngService
{
    public function get($id)
    {
        $ong = Ong::find($id);

        if(!$ong) {
            throw ValidationException::withMessages(['ONG não encontrada.']);
        }

        return $ong->load('specialities');
    }

    public function getAll()
    {
        $ongs = Ong::with('specialities')->get();

        if(!$ongs) {
            throw ValidationException::withMessages(['Não há ONGs salvas.']);
        }

        return response()->json(['data' => $ongs->each(fn ($ong) => $ong->image = $ong->image_url)]);
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

            CalculateDistanceOngSide::dispatch($ong->id);

            $ong->image = $ong->image_url;

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

        $image_source = null;
        if ($request->hasFile('image')) {
            $image_source = $request->file('image')->store('ongs');
        }

        try  {
            DB::beginTransaction();
                $ong->name = $request->input('name');
                $ong->specialities()->detach();
                $ong->specialities()->attach($request->input('specialities_ids'));

                if ($ong->image) {
                    Storage::delete($ong->image);
                }
                $ong->image = $image_source;

                $ong->save();
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
