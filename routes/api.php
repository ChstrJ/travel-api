<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Admin\TravelController as AdminTravelController;
use \App\Http\Controllers\Api\V1\Admin\TourController as AdminTourController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/travels', TravelController::class);
Route::get('/travels/{travel:slug}/tours', [TourController::class, 'index']);


Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('travels', [AdminTravelController::class, 'store']);
    Route::post('travels/{travel}/tours', [AdminTourController::class, 'store']);
    Route::get('travels/{travel}/tours', [AdminTourController::class, 'index']);
});

Route::post('/auth/login', LoginController::class);
