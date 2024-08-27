<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\master\ApiController;
use App\Http\Controllers\master\RoleController;
use App\Http\Controllers\master\AdminController;
use App\Http\Controllers\master\ApiRoleController;
use App\Http\Controllers\master\FeatureController;
use App\Http\Controllers\master\CategoryController;
use App\Http\Controllers\master\AdminRoleController;

Route::prefix('admin')->group(function () {
  Route::post('login', [AdminController::class, 'login']);

  Route::middleware(AdminMiddleware::class)->group(function () {

    // Admin
    Route::post('register', [AdminController::class, 'register']);
    Route::get('me', [AdminController::class, 'me']);
    Route::post('logout', [AdminController::class, 'logout']);

    // Role
    Route::prefix('role')->group(function () {
      Route::get('list', [RoleController::class, 'list']);
      Route::post('store', [RoleController::class, 'store']);
      Route::put('update/{id}', [RoleController::class, 'update']);
      Route::delete('delete/{id}', [RoleController::class, 'delete']);
    });

    // Admin role
    Route::prefix('admin-role')->group(function () {
      Route::get('list', [AdminRoleController::class, 'list']);
      Route::put('update', [AdminRoleController::class, 'update']);
    });

    // Feature
    Route::prefix('feature')->group(function () {
      Route::get('list', [FeatureController::class, 'list']);
      Route::post('store', [FeatureController::class, 'store']);
      Route::put('update/{id}', [FeatureController::class, 'update']);
      Route::delete('delete/{id}', [FeatureController::class, 'delete']);
    });

    // Api
    Route::prefix('api')->group(function () {
      Route::get('list', [ApiController::class, 'list']);
      Route::post('store', [ApiController::class, 'store']);
      Route::put('update/{id}', [ApiController::class, 'update']);
      Route::delete('delete/{id}', [ApiController::class, 'delete']);
    });

    // Api role
    Route::prefix('api-role')->group(function () {
      Route::get('list', [ApiRoleController::class, 'list']);
      Route::put('update', [ApiRoleController::class, 'update']);
    });

    Route::apiResources([
      'category' => CategoryController::class
    ]);
  });
});

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
