<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpaceAvailabilityController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Space routes
    Route::get('/spaces', [SpaceController::class, 'index']);
    Route::post('/spaces', [SpaceController::class, 'store']);
    Route::put('/spaces/{space}', [SpaceController::class, 'update']);
    Route::delete('/spaces/{space}', [SpaceController::class, 'destroy']);
    Route::get('/spaces/{space}/availability', [
        SpaceAvailabilityController::class,
        'check'
    ]);

    // Reservation routes
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::get('/reservations/user/{userId}', [ReservationController::class, 'showAllByUser']);
    Route::get('/reservations/space/{spaceId}', [ReservationController::class, 'showAllBySpace']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
});

Route::get('/test', fn () => 'API works');