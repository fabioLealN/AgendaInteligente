<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Controller;
use App\Models\TypeUser;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request, AddressController $addressController)
    {
        $request->validate([
            'name' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'type_user_id' => ['required']
        ]);

        $ongsIds = $specialitiesIds = null;
        if ($request->input('type_user_id') === TypeUser::TYPE_ONG) {
            $request->validate([
                'ongs_ids' => ['required', 'array'],
                'specialities_ids' => ['required', 'array']
            ]);

            $ongsIds = $request->input('ongs_ids');
            $specialitiesIds = $request->input('specialities_ids');
        }

        $address = $addressController->store($request);

        $userData = $request->only('name', 'email', 'password', 'phone', 'type_user_id');
        $userData['address_id'] = $address->id;

        return $this->userService->store($userData, $ongsIds, $specialitiesIds);
    }
}
