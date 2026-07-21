<?php

use App\Http\Controllers\Api\AdvertisingObjectController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\PhotoReportController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('contracts', ContractController::class);

    Route::apiResource('advertising-objects', AdvertisingObjectController::class);

    Route::apiResource('photo-reports', PhotoReportController::class);

    Route::apiResource('photos', PhotoController::class)->middleware('auth:sanctum');
});
