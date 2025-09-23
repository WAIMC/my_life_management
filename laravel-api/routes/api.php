<?php

use App\Http\Controllers\History\Management\BannerMgmtHistController;
use App\Http\Controllers\History\Management\CategoryMgmtHistController;
use App\Http\Controllers\History\Master\AdminMstHistController;
use App\Http\Controllers\History\Master\ApiMstHistController;
use App\Http\Controllers\History\Master\DepartmentMstHistController;
use App\Http\Controllers\History\Master\FeatureMstHistController;
use App\Http\Controllers\History\Master\LanguageMstHistController;
use App\Http\Controllers\Management\BannerMgmtController;
use App\Http\Controllers\Management\CategoryMgmtController;
use App\Http\Controllers\Management\CategorySkillMgmtController;
use App\Http\Controllers\Master\AdminDepartmentMstController;
use App\Http\Controllers\Master\AdminMstController;
use App\Http\Controllers\Master\AdminRoleMstController;
use App\Http\Controllers\Master\ApiMstController;
use App\Http\Controllers\Master\ApiRoleMstController;
use App\Http\Controllers\Master\DepartmentManagementMstController;
use App\Http\Controllers\Master\DepartmentMstController;
use App\Http\Controllers\Master\FeatureMstController;
use App\Http\Controllers\Master\LanguageMstController;
use App\Http\Controllers\Master\OriginalTranslatorMstController;
use App\Http\Controllers\Master\PolicyDepartmentMstController;
use App\Http\Controllers\Master\RoleMstController;
use App\Http\Controllers\Master\SkillMstController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('login', [AdminMstController::class, 'login']);
        Route::post('refresh-token', [AdminMstController::class, 'refreshToken']);
    });

    Route::middleware(AdminMiddleware::class)->group(function () {

        // Master
        Route::prefix('master')->group(function () {
            // Admin
            Route::prefix('account')->group(function () {
                Route::post('logout', [AdminMstController::class, 'logout']);
                Route::get('list', [AdminMstController::class, 'list']);
                Route::post('store', [AdminMstController::class, 'store']);
                Route::put('update/{id}', [AdminMstController::class, 'update']);
                Route::delete('delete/{id}', [AdminMstController::class, 'delete']);
            });

            // Role
            Route::apiResource('role', RoleMstController::class);

            // Admin role
            Route::prefix('admin-role')->group(function () {
                Route::get('list', [AdminRoleMstController::class, 'list']);
                Route::put('update', [AdminRoleMstController::class, 'update']);
            });

            // Feature
            Route::apiResource('feature', FeatureMstController::class);

            // Api
            Route::prefix('api')->group(function () {
                Route::get('list', [ApiMstController::class, 'list']);
                Route::post('store', [ApiMstController::class, 'store']);
                Route::put('update/{id}', [ApiMstController::class, 'update']);
                Route::delete('delete/{id}', [ApiMstController::class, 'delete']);
            });

            // Api role
            Route::prefix('api-role')->group(function () {
                Route::get('list', [ApiRoleMstController::class, 'list']);
                Route::put('update', [ApiRoleMstController::class, 'update']);
            });

            // Department
            Route::apiResource('department', DepartmentMstController::class);
            Route::get('department/code/{code}', [DepartmentMstController::class, 'getByCode']);

            // Admin department
            Route::prefix('admin-department')->group(function () {
                Route::get('list', [AdminDepartmentMstController::class, 'list']);
                Route::put('update', [AdminDepartmentMstController::class, 'update']);
            });

            // Policy Department
            Route::apiResource('policy-department', PolicyDepartmentMstController::class);
            Route::get('policy-department/table/{tableName}', [PolicyDepartmentMstController::class, 'getByTableName']);
            Route::get('policy-department/table/{tableName}/row/{rowId}', [PolicyDepartmentMstController::class, 'getByTableNameAndRowId']);

            // Department Management
            Route::get('department-management', [DepartmentManagementMstController::class, 'index']);
            Route::post('department-management', [DepartmentManagementMstController::class, 'store']);
            Route::get('department-management/{departmentId}/{policyDepartmentId}', [DepartmentManagementMstController::class, 'show']);
            Route::delete('department-management/{departmentId}/{policyDepartmentId}', [DepartmentManagementMstController::class, 'destroy']);
            Route::get('department-management/department/{departmentId}', [DepartmentManagementMstController::class, 'getByDepartmentId']);
            Route::get('department-management/policy-department/{policyDepartmentId}', [DepartmentManagementMstController::class, 'getByPolicyDepartmentId']);

            // Language
            Route::group(['prefix' => 'language'], function () {
                Route::get('/', [LanguageMstController::class, 'index']);
                Route::get('/{id}', [LanguageMstController::class, 'show']);
                Route::post('/', [LanguageMstController::class, 'store']);
                Route::put('/{id}', [LanguageMstController::class, 'update']);
                Route::delete('/{id}', [LanguageMstController::class, 'destroy']);
            });

            // Original translator
            Route::prefix('original-translator')->group(function () {
                Route::get('list', [OriginalTranslatorMstController::class, 'list']);
                Route::get('{id}', [OriginalTranslatorMstController::class, 'show']);
                Route::post('store', [OriginalTranslatorMstController::class, 'store']);
                Route::put('update/{id}', [OriginalTranslatorMstController::class, 'update']);
                Route::delete('delete/{id}', [OriginalTranslatorMstController::class, 'delete']);
            });
        });

        // Management
        Route::prefix('management')->group(function () {
            // Category
            Route::prefix('category')->group(function () {
                Route::get('/', [CategoryMgmtController::class, 'index']);
                Route::post('/', [CategoryMgmtController::class, 'store']);
                Route::get('/{id}', [CategoryMgmtController::class, 'show']);
                Route::put('/{id}', [CategoryMgmtController::class, 'update']);
                Route::delete('/{id}', [CategoryMgmtController::class, 'destroy']);
                Route::get('/parent/{parentId}', [CategoryMgmtController::class, 'getByParentId']);
                Route::get('/root/list', [CategoryMgmtController::class, 'getRootCategories']);
            });

            // Skill
            Route::prefix('skill')->group(function () {
                Route::get('list', [SkillMstController::class, 'list']);
                Route::post('store', [SkillMstController::class, 'store']);
                Route::put('update/{id}', [SkillMstController::class, 'update']);
                Route::delete('delete/{id}', [SkillMstController::class, 'delete']);
            });

            // Banner
            Route::prefix('banner')->group(function () {
                Route::get('/', [BannerMgmtController::class, 'index']);
                Route::post('/', [BannerMgmtController::class, 'store']);
                Route::get('/{id}', [BannerMgmtController::class, 'show']);
                Route::put('/{id}', [BannerMgmtController::class, 'update']);
                Route::delete('/{id}', [BannerMgmtController::class, 'destroy']);
            });

            // Category skill
            Route::prefix('category-skill')->group(function () {
                Route::get('/', [CategorySkillMgmtController::class, 'index']);
                Route::get('/category/{categoryId}/skills', [CategorySkillMgmtController::class, 'getSkillsByCategoryId']);
                Route::get('/skill/{skillId}/categories', [CategorySkillMgmtController::class, 'getCategoriesBySkillId']);
                Route::post('/category/{categoryId}/attach', [CategorySkillMgmtController::class, 'attachSkill']);
                Route::delete('/category/{categoryId}/skill/{skillId}', [CategorySkillMgmtController::class, 'detachSkill']);
                Route::put('/category/{categoryId}/sync', [CategorySkillMgmtController::class, 'syncSkills']);
            });
        });

        // Master history
        Route::prefix('history/master')->group(function () {
            // Admin
            Route::apiResource('admin', AdminMstHistController::class);

            // API
            Route::prefix('api')->group(function () {
                Route::get('/', [ApiMstHistController::class, 'index']);
                Route::post('/', [ApiMstHistController::class, 'store']);
                Route::get('/{id}', [ApiMstHistController::class, 'show']);
                Route::get('/by-api/{apiMstId}', [ApiMstHistController::class, 'getByApiMstId']);
            });

            // Department
            Route::prefix('department')->group(function () {
                Route::get('/', [DepartmentMstHistController::class, 'index']);
                Route::post('/', [DepartmentMstHistController::class, 'store']);
                Route::get('/{id}', [DepartmentMstHistController::class, 'show']);
                Route::put('/{id}', [DepartmentMstHistController::class, 'update']);
                Route::delete('/{id}', [DepartmentMstHistController::class, 'destroy']);
                Route::get('/by-department/{departmentMstId}', [DepartmentMstHistController::class, 'getByDepartmentId']);
            });

            // Feature
            Route::prefix('feature')->group(function () {
                Route::get('/', [FeatureMstHistController::class, 'index']);
                Route::post('/', [FeatureMstHistController::class, 'store']);
                Route::get('/{id}', [FeatureMstHistController::class, 'show']);
                Route::put('/{id}', [FeatureMstHistController::class, 'update']);
                Route::delete('/{id}', [FeatureMstHistController::class, 'destroy']);
                Route::get('/by-feature/{featureMstId}', [FeatureMstHistController::class, 'getByFeatureId']);
            });

            // Language
            Route::prefix('language')->group(function () {
                Route::get('list', [LanguageMstHistController::class, 'list']);
                Route::get('{id}', [LanguageMstHistController::class, 'show']);
                Route::post('store', [LanguageMstHistController::class, 'store']);
                Route::put('update/{id}', [LanguageMstHistController::class, 'update']);
                Route::delete('delete/{id}', [LanguageMstHistController::class, 'delete']);
            });

        });

        // Management history
        Route::prefix('history/management')->group(function () {

            // Banner
            Route::prefix('banner')->group(function () {
                Route::get('/', [BannerMgmtHistController::class, 'index']);
                Route::post('/', [BannerMgmtHistController::class, 'store']);
                Route::get('/{id}', [BannerMgmtHistController::class, 'show']);
                Route::get('/by-banner/{bannerId}', [BannerMgmtHistController::class, 'getByBannerId']);
            });

            // Category
            Route::prefix('category')->group(function () {
                Route::get('/', [CategoryMgmtHistController::class, 'index']);
                Route::post('/', [CategoryMgmtHistController::class, 'store']);
                Route::get('/{id}', [CategoryMgmtHistController::class, 'show']);
                Route::get('/by-category/{categoryId}', [CategoryMgmtHistController::class, 'getByCategoryId']);
            });
        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
