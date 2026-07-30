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

    Route::apiResource('contracts', ContractController::class)->names('api.contracts');

    Route::apiResource('advertising-objects', AdvertisingObjectController::class)->names('api.advertising-objects');

    Route::apiResource('photo-reports', PhotoReportController::class)->names('api.photo-reports');

    Route::apiResource('photos', PhotoController::class)->middleware('auth:sanctum');
});
