<?php

namespace App\Http\Controllers;

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

    public function update(Request $request) {
        $request->validate([
            'name' => ['required'],
            'phone' => ['required'],
        ]);

        return $this->userService->update($request);
    }
}
