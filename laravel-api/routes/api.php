<?php

use App\Http\Controllers\History\Management\BannerMgmtHistController;
use App\Http\Controllers\History\Master\AdminMstHistController;
use App\Http\Controllers\History\Master\ApiMstHistController;
use App\Http\Controllers\Management\BannerMgmtController;
use App\Http\Controllers\Management\CategoryMgmtController;
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

            // Department Master
            Route::apiResource('departments', DepartmentMstController::class);
            Route::get('departments/code/{code}', [DepartmentMstController::class, 'getByCode']);

            // AdminMst department
            Route::prefix('admin-department')->group(function () {
                Route::get('list', [AdminDepartmentMstController::class, 'list']);
                Route::put('update', [AdminDepartmentMstController::class, 'update']);
            });

            // Policy Department Master
            Route::apiResource('policy-departments', PolicyDepartmentMstController::class);
            Route::get('policy-departments/table/{tableName}', [PolicyDepartmentMstController::class, 'getByTableName']);
            Route::get('policy-departments/table/{tableName}/row/{rowId}', [PolicyDepartmentMstController::class, 'getByTableNameAndRowId']);

            // Department Management master
            Route::get('department-managements', [DepartmentManagementMstController::class, 'index']);
            Route::post('department-managements', [DepartmentManagementMstController::class, 'store']);
            Route::get('department-managements/{departmentId}/{policyDepartmentId}', [DepartmentManagementMstController::class, 'show']);
            Route::delete('department-managements/{departmentId}/{policyDepartmentId}', [DepartmentManagementMstController::class, 'destroy']);
            Route::get('department-managements/department/{departmentId}', [DepartmentManagementMstController::class, 'getByDepartmentId']);
            Route::get('department-managements/policy-department/{policyDepartmentId}', [DepartmentManagementMstController::class, 'getByPolicyDepartmentId']);
        });

        // Management
        Route::prefix('management')->group(function () {
            // Category management
            Route::prefix('categories')->group(function () {
                Route::get('/', [CategoryMgmtController::class, 'index']);
                Route::post('/', [CategoryMgmtController::class, 'store']);
                Route::get('/{id}', [CategoryMgmtController::class, 'show']);
                Route::put('/{id}', [CategoryMgmtController::class, 'update']);
                Route::delete('/{id}', [CategoryMgmtController::class, 'destroy']);
                Route::get('/parent/{parentId}', [CategoryMgmtController::class, 'getByParentId']);
                Route::get('/root/list', [CategoryMgmtController::class, 'getRootCategories']);
            });

            // Skill management
            Route::prefix('skill')->group(function () {
                Route::get('list', [SkillMstController::class, 'list']);
                Route::post('store', [SkillMstController::class, 'store']);
                Route::put('update/{id}', [SkillMstController::class, 'update']);
                Route::delete('delete/{id}', [SkillMstController::class, 'delete']);
            });

            // Banner management
            Route::prefix('banners')->group(function () {
                Route::get('/', [BannerMgmtController::class, 'index']);
                Route::post('/', [BannerMgmtController::class, 'store']);
                Route::get('/{id}', [BannerMgmtController::class, 'show']);
                Route::put('/{id}', [BannerMgmtController::class, 'update']);
                Route::delete('/{id}', [BannerMgmtController::class, 'destroy']);
            });
        });

        // Master history
        Route::prefix('history/master')->group(function () {
            // Admin History CRUD routes
            Route::apiResource('admin-master-histories', AdminMstHistController::class);

            // API master history
            Route::prefix('api-master-hist')->group(function () {
                Route::get('/', [ApiMstHistController::class, 'index']);
                Route::post('/', [ApiMstHistController::class, 'store']);
                Route::get('/{id}', [ApiMstHistController::class, 'show']);
                Route::get('/by-api/{apiMstId}', [ApiMstHistController::class, 'getByApiMstId']);
            });
        });

        // Management history
        Route::prefix('history/management')->group(function () {

            // Banner management history
            Route::prefix('banner-histories')->group(function () {
                Route::get('/', [BannerMgmtHistController::class, 'index']);
                Route::post('/', [BannerMgmtHistController::class, 'store']);
                Route::get('/{id}', [BannerMgmtHistController::class, 'show']);
                Route::get('/by-banner/{bannerId}', [BannerMgmtHistController::class, 'getByBannerId']);
            });

        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
