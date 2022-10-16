<?php

use App\Http\Controllers\Auth\Api\LoginController;
use App\Http\Controllers\Auth\Api\RegisterController;
use App\Http\Controllers\OngController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])
        ->middleware('auth:sanctum');
});

Route::prefix('user')->group(function () {
    Route::put('update', [UserController::class, 'updateUser'])
        ->middleware('auth:sanctum');
});

Route::prefix('pets')->group(function () {
    Route::get('/', [PetController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [PetController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [PetController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [PetController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix('ongs')->group(function () {
    Route::get('/', [OngController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [OngController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [OngController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [OngController::class, 'update'])->middleware('auth:sanctum');
});
