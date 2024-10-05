<?php

use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
	return response()->json(['message' => 'API работает!']);
});

Route::post('/locations', [LocationController::class, 'store']);
Route::get('/locations/{locationName}/weather', [LocationController::class, 'getAverageWeather']);