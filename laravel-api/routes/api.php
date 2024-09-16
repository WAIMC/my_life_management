<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\master\ApiController;
use App\Http\Controllers\master\RoleController;
use App\Http\Controllers\master\AdminController;
use App\Http\Controllers\master\SkillController;
use App\Http\Controllers\master\ApiRoleController;
use App\Http\Controllers\master\FeatureController;
use App\Http\Controllers\master\CategoryController;
use App\Http\Controllers\master\AdminRoleController;
use App\Http\Controllers\master\DepartmentController;
use App\Http\Controllers\master\AdminDepartmentController;
use App\Http\Controllers\master\PolicyDepartmentController;
use App\Http\Controllers\master\DepartmentManagementController;

Route::prefix('admin')->group(function () {
  Route::post('login', [AdminController::class, 'login']);

  Route::middleware(AdminMiddleware::class)->group(function () {

    // Admin
    Route::get('me', [AdminController::class, 'me']);
    Route::post('logout', [AdminController::class, 'logout']);
    Route::get('list', [AdminController::class, 'list']);
    Route::post('store', [AdminController::class, 'store']);
    Route::put('update/{id}', [AdminController::class, 'update']);
    Route::delete('delete/{id}', [AdminController::class, 'delete']);

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

    // Department
    Route::prefix('department')->group(function () {
      Route::get('list', [DepartmentController::class, 'list']);
      Route::post('store', [DepartmentController::class, 'store']);
      Route::put('update/{id}', [DepartmentController::class, 'update']);
      Route::delete('delete/{id}', [DepartmentController::class, 'delete']);
    });

    // Admin department
    Route::prefix('admin-department')->group(function () {
      Route::get('list', [AdminDepartmentController::class, 'list']);
      Route::put('update', [AdminDepartmentController::class, 'update']);
    });

    // Policy department
    Route::prefix('policy-department')->group(function () {
      Route::get('list', [PolicyDepartmentController::class, 'list']);
      Route::post('store', [PolicyDepartmentController::class, 'store']);
      Route::put('update/{id}', [PolicyDepartmentController::class, 'update']);
      Route::delete('delete/{id}', [PolicyDepartmentController::class, 'delete']);
    });

    // Department management
    Route::prefix('department-management')->group(function () {
      Route::get('list', [DepartmentManagementController::class, 'list']);
      Route::put('update', [DepartmentManagementController::class, 'update']);
    });

    // Category
    Route::prefix('category')->group(function () {
      Route::get('list', [CategoryController::class, 'list']);
      Route::post('store', [CategoryController::class, 'store']);
      Route::put('update/{id}', [CategoryController::class, 'update']);
      Route::delete('delete/{id}', [CategoryController::class, 'delete']);
    });

    // Skill
    Route::prefix('skill')->group(function () {
      Route::get('list', [SkillController::class, 'list']);
      Route::post('store', [SkillController::class, 'store']);
      Route::put('update/{id}', [SkillController::class, 'update']);
      Route::delete('delete/{id}', [SkillController::class, 'delete']);
    });
  });
});

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
