<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\Api\LoginController;
use App\Http\Controllers\Auth\Api\RegisterController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\OngController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\TypeSchedulingController;
use App\Http\Controllers\UserController;
use Database\Seeders\DatabaseSeederProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/teste', function() {
    return "o meu deus";
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('run-artisan')->group(function () {
    Route::get('queue', function () {
        Artisan::call('queue:work');
    });

    Route::get('schedule', function () {
        Artisan::call('schedule:work');
    });

    Route::get('seed', [DatabaseSeederProduction::class, 'run']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('user')->group(function () {
    Route::put('{id}', [UserController::class, 'get'])->middleware('auth:sanctum');
    Route::put('update', [UserController::class, 'update'])->middleware('auth:sanctum');
    Route::get('{user}/pets', [UserController::class, 'getPets'])->middleware('auth:sanctum');
    Route::get('specialists', [UserController::class, 'getSpecialists'])->middleware('auth:sanctum');
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
    Route::get('{ong}/specialists', [OngController::class, 'getSpecialists'])->middleware('auth:sanctum');
    Route::get('{ong}/schedules', [OngController::class, 'getSchedules'])->middleware('auth:sanctum');
    Route::get('{ong}/next-schedules', [OngController::class, 'getNextSchedules'])->middleware('auth:sanctum');
    Route::post('/', [OngController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/{ong}/specialists/{specialist}', [OngController::class, 'attachSpecialist'])->middleware('auth:sanctum');
    Route::delete('/{ong}/specialists/{specialist}', [OngController::class, 'dettachSpecialist'])->middleware('auth:sanctum');
    Route::put('{id}', [OngController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix('specialities')->group(function () {
    Route::get('/', [SpecialityController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [SpecialityController::class, 'get'])->middleware('auth:sanctum');
    Route::get('{id}/ongs', [SpecialityController::class, 'getOngs'])->middleware('auth:sanctum');
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

Route::prefix('types-schedulings')->group(function () {
    Route::get('/', [TypeSchedulingController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('{id}', [TypeSchedulingController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [TypeSchedulingController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [TypeSchedulingController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix('schedules')->group(function () {
    Route::get('/', [ScheduleController::class, 'getAllAvailable'])->middleware('auth:sanctum');
    Route::get('{id}', [ScheduleController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [ScheduleController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [ScheduleController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix('schedulings')->group(function () {
    Route::get('/', [SchedulingController::class, 'getAll'])->middleware('auth:sanctum');
    Route::get('/next-schedulings', [SchedulingController::class, 'getFutureSchedulings'])->middleware('auth:sanctum');
    Route::get('{id}', [SchedulingController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [SchedulingController::class, 'store'])->middleware('auth:sanctum');
    Route::put('{id}', [SchedulingController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('{id}', [SchedulingController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('addresses')->group(function () {
    Route::get('{id}', [AddressController::class, 'get'])->middleware('auth:sanctum');
    Route::put('{id}', [AddressController::class, 'update'])->middleware('auth:sanctum');
});
