<?php

use App\Http\Controllers\Auth\Api\LoginController;
use App\Http\Controllers\Auth\Api\RegisterController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\OngController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SpecialityController;
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

Route::prefix('specialities')->group(function () {
    Route::get('/', [SpecialityController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [SpecialityController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [SpecialityController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [SpecialityController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix('breeds')->group(function () {
    Route::get('/', [BreedController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [BreedController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [BreedController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [BreedController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix('sizes')->group(function () {
    Route::get('/', [SizeController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [SizeController::class, 'get'])->middleware('auth:sanctum');
});
