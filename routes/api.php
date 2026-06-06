<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\Food2Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('foods', FoodController::class);

Route::get('/foods', [FoodController::class, 'index']);
Route::post('/foods', [FoodController::class, 'store']);
Route::get('/foods/search', [FoodController::class, 'search']);
Route::get('/foods/trashed', [FoodController::class, 'trashed']);

Route::get('/foods/{food}', [FoodController::class, 'show']);
Route::put('/foods/{food}', [FoodController::class, 'update']);

Route::delete('/foods/{food}', [FoodController::class, 'destroy']);

// Route::patch('/foods/{id}/restore', [FoodController::class, 'restore']);
// Route::delete('/foods/{id}/force', [FoodController::class, 'forceDelete']);

Route::get('/foods2', [Food2Controller::class, 'index']);
Route::post('/foods2', [Food2Controller::class, 'store']);

Route::get('/foods2/{food}', [Food2Controller::class, 'show']);
Route::put('/foods2/{food}', [Food2Controller::class, 'update']);
Route::put('/foods2/{food}', [Food2Controller::class, 'destroy']);