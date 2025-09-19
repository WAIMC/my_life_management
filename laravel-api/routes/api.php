<?php

use App\Http\Controllers\History\Master\AdminMstHistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Master\ApiMstController;
use App\Http\Controllers\Master\RoleMstController;
use App\Http\Controllers\Master\AdminMstController;
use App\Http\Controllers\Master\SkillMstController;
use App\Http\Controllers\Master\ApiRoleMstController;
use App\Http\Controllers\Master\FeatureMstController;
use App\Http\Controllers\Master\CategoryMstController;
use App\Http\Controllers\Master\AdminRoleMstController;
use App\Http\Controllers\Master\DepartmentMstController;
use App\Http\Controllers\Master\AdminDepartmentMstController;
use App\Http\Controllers\Master\PolicyDepartmentMstController;
use App\Http\Controllers\Master\DepartmentManagementMstController;

Route::prefix('admin')->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('login', [AdminMstController::class, 'login']);
        Route::post('refresh-token', [AdminMstController::class, 'refreshToken']);
    });

    Route::middleware(AdminMiddleware::class)->group(function () {

        // DepartmentMst master
        Route::prefix('master')->group(function () {
            // AdminMst
            Route::prefix('account')->group(function () {
                Route::post('logout', [AdminMstController::class, 'logout']);
                Route::get('list', [AdminMstController::class, 'list']);
                Route::post('store', [AdminMstController::class, 'store']);
                Route::put('update/{id}', [AdminMstController::class, 'update']);
                Route::delete('delete/{id}', [AdminMstController::class, 'delete']);
            });

            // Role Master
            Route::apiResource('roles', RoleMstController::class);

            // AdminMst role
            Route::prefix('admin-role')->group(function () {
                Route::get('list', [AdminRoleMstController::class, 'list']);
                Route::put('update', [AdminRoleMstController::class, 'update']);
            });

            // Feature Master
            Route::apiResource('features', FeatureMstController::class);

            // ApiMst
            Route::prefix('api')->group(function () {
                Route::get('list', [ApiMstController::class, 'list']);
                Route::post('store', [ApiMstController::class, 'store']);
                Route::put('update/{id}', [ApiMstController::class, 'update']);
                Route::delete('delete/{id}', [ApiMstController::class, 'delete']);
            });

            // ApiMst role
            Route::prefix('api-role')->group(function () {
                Route::get('list', [ApiRoleMstController::class, 'list']);
                Route::put('update', [ApiRoleMstController::class, 'update']);
            });

            // DepartmentMst
            Route::prefix('department')->group(function () {
                Route::get('list', [DepartmentMstController::class, 'list']);
                Route::post('store', [DepartmentMstController::class, 'store']);
                Route::put('update/{id}', [DepartmentMstController::class, 'update']);
                Route::delete('delete/{id}', [DepartmentMstController::class, 'delete']);
            });

            // AdminMst department
            Route::prefix('admin-department')->group(function () {
                Route::get('list', [AdminDepartmentMstController::class, 'list']);
                Route::put('update', [AdminDepartmentMstController::class, 'update']);
            });

            // Policy department
            Route::prefix('policy-department')->group(function () {
                Route::get('list', [PolicyDepartmentMstController::class, 'list']);
                Route::post('store', [PolicyDepartmentMstController::class, 'store']);
                Route::put('update/{id}', [PolicyDepartmentMstController::class, 'update']);
                Route::delete('delete/{id}', [PolicyDepartmentMstController::class, 'delete']);
            });

            // Department Management master
            Route::get('department-managements', [DepartmentManagementMstController::class, 'index']);
            Route::post('department-managements', [DepartmentManagementMstController::class, 'store']);
            Route::get('department-managements/{departmentId}/{policyDepartmentId}', [DepartmentManagementMstController::class, 'show']);
            Route::delete('department-managements/{departmentId}/{policyDepartmentId}', [DepartmentManagementMstController::class, 'destroy']);
            Route::get('department-managements/department/{departmentId}', [DepartmentManagementMstController::class, 'getByDepartmentId']);
            Route::get('department-managements/policy-department/{policyDepartmentId}', [DepartmentManagementMstController::class, 'getByPolicyDepartmentId']);
        });

        // DepartmentMst management
        Route::prefix('management')->group(function () {
            // CategoryMgmt
            Route::prefix('category')->group(function () {
                Route::get('list', [CategoryMstController::class, 'list']);
                Route::post('store', [CategoryMstController::class, 'store']);
                Route::put('update/{id}', [CategoryMstController::class, 'update']);
                Route::delete('delete/{id}', [CategoryMstController::class, 'delete']);
            });

            // SkillMgmt
            Route::prefix('skill')->group(function () {
                Route::get('list', [SkillMstController::class, 'list']);
                Route::post('store', [SkillMstController::class, 'store']);
                Route::put('update/{id}', [SkillMstController::class, 'update']);
                Route::delete('delete/{id}', [SkillMstController::class, 'delete']);
            });
        });

        // DepartmentMst master history
        Route::prefix('history/master')->group(function () {
            // Admin History CRUD routes
            Route::apiResource('admin-histories', AdminMstHistController::class);
        });

        // DepartmentMst management history
        Route::prefix('history/management')->group(function () {

        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
