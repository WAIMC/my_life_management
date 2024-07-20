<?php

use App\Http\Controllers\master\AdminController;
use App\Http\Controllers\master\CategoryController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
  Route::post('register', [AdminController::class, 'register']);
  Route::post('login', [AdminController::class, 'login']);

  Route::middleware(AdminMiddleware::class)->group(function () {
    Route::get('me', [AdminController::class, 'me']);
    Route::apiResources([
      'category' => CategoryController::class
    ]);
  });
});

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
