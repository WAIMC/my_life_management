<?php

use App\Http\Controllers\master\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
  Route::apiResources([
    'category' => CategoryController::class
  ]);
});

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
