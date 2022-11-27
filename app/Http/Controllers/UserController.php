<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get(int $id)
    {
        try
        {
            return $this->userService->get($id);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getPets(User $user)
    {
        $pets = $user->pets->load(['breed', 'size']);
        return response()->json(['data' => $pets]);
    }

    public function getSpecialists()
    {
        $specialists = User::where('type_user_id', 1)->whereHas('specialities')->with('specialities')->get();

        return response()->json(['data' => $specialists]);
    }

    public function update(Request $request) {
        $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required'],
            'type_user_id' => ['required', 'integer'],
            'specialities_ids' => ['required', 'array'],
            'ongs_ids' => ['required', 'array'],
        ]);

        return $this->userService->update($request);
    }

}
