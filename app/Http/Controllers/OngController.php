<?php

namespace App\Http\Controllers;

use App\Models\Ong;
use App\Models\User;
use App\Services\OngService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OngController extends Controller
{
    private $ongService;

    public function __construct(OngService $ongService)
    {
        $this->ongService = $ongService;
    }

    public function get($id)
    {
        try
        {
            return response()->json(['data' => $this->ongService->get($id)]);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getAll()
    {
        try
        {
            return $this->ongService->getAll();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function getSpecialists(Ong $ong)
    {
        $data = $ong->specialists->load(['user', 'user.specialities']);
        return response()->json(['data' => $data]);
    }

    public function getSchedules(Ong $ong)
    {
        $data = $ong->specialists()->with(['schedules', 'user', 'speciality'])->get()->groupBy('user.id');
        return response()->json(['data' => $data]);
    }

    public function store(Request $request, AddressController $addressController)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'specialities_ids' => ['required', 'array'],
            'image' => ['nullable', 'image']
        ]);

        $image_source = null;
        if ($request->hasFile('image')) {
            $image_source = $request->file('image')->store('ongs');
        }

        try
        {
            $address = $addressController->store($request);

            $ongData = $request->only('name', 'specialities_ids');
            $ongData['address_id'] = $address->id;
            $ongData['image'] = $image_source;

            return $this->ongService->store($ongData);
        }
        catch (ValidationException $e)
        {
            $addressController->delete($address->id);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'specialities_ids' => ['required', 'array'],
            'image' => ['nullable', 'image']
        ]);

        return $this->ongService->update($id, $request);
    }

    public function attachSpecialist(Request $request, Ong $ong, User $specialist)
    {
        $specialities = $specialist->specialities->pluck('pivot.id');
        $ong->specialists()->attach($specialities);
        $ong->load('specialists');

        return response()->json(['data' => $ong]);
    }

    public function dettachSpecialist(Request $request, Ong $ong, User $specialist)
    {
        $specialities = $specialist->specialities->pluck('pivot.id');
        $ong->specialists()->detach($specialities);
        $ong->load('specialists');

        return response()->json(['data' => $ong]);
    }
}
