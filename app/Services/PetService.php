<?php

namespace App\Services;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PetService
{
    public function get(int $id)
    {
        $pet = Pet::where('id', $id)->where('user_id', Auth::user()->id)->get()[0];

        if(!$pet) {
            throw ValidationException::withMessages(['Animal não encontrado']);
        }

        $pet->image = $pet->image_url;

        return $pet;
    }

    public function getAll()
    {
        $user = User::find(Auth::user()->id);

        $pets = $user->pets;

        if(!$pets) {
            throw ValidationException::withMessages(['Não há animais salvos.']);
        }

        return response()->json(['data' => $pets->each(fn ($pet) => $pet->image = $pet->image_url)]);
    }

    public function store(array $petData)
    {
        if ($this->validateBreedAndSize($petData['breed_id'], $petData['size_id'])) {
            return response()->json(['error' => 'Porte e/ou raça não encontrados.'], 404);
        }

        $petData['user_id'] = Auth::user()->id;

        try  {
            $pet = Pet::create($petData);

            $pet->image = $pet->image_url;

            return response()->json(['data' => ['pet' => $pet]], 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    public function update(int $id, Request $request)
    {
        $pet = Pet::findOrFail($id);

        if($this->validateBreedAndSize($request->input('breed_id'), $request->input('size_id'))) {
            return response()->json(['error' => 'Ocorreu um erro! Dados não encontrados.'], 404);
        }

        $image_source = null;
        if ($request->hasFile('image')) {
            $image_source = $request->file('image')->store('ongs');
        }

        try
        {
            $pet->name = $request->input('name');
            $pet->birth_date = $request->input('birth_date');
            $pet->breed_id = $request->input('breed_id');
            $pet->size_id = $request->input('size_id');

            if ($pet->image) {
                Storage::delete($pet->image);
            }
            $pet->image = $image_source;

            $pet->save();

            return response()->json(['data' => ['status' => 'Atualizado com sucesso!']], 200);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage(), 422]);
        }
    }

    private function validateBreedAndSize(int $breed_id, int $size_id): void
    {
        Breed::findOrFail($breed_id);
        Size::findOrFail($size_id);
    }
}
