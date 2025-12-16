<?php

use App\Http\Controllers\Custom\CredentialController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GenerateResponseMiddleware;
use App\Http\Middleware\TransactionMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\AdminMstController;
use App\Http\Controllers\Master\RoleMstController;
use App\Http\Controllers\Master\DepartmentMstController;
use App\Http\Controllers\Master\FeatureMstController;
use App\Http\Controllers\Master\ApiMstController;
use App\Http\Controllers\Master\LanguageMstController;
use App\Http\Controllers\Master\TranslationMstController;
use App\Http\Controllers\Master\TokenMstController;
use App\Http\Controllers\Master\PolicyDepartmentMstController;
use App\Http\Controllers\Master\OriginalTranslatorMstController;
use App\Http\Controllers\Master\AdminDepartmentMstController;
use App\Http\Controllers\Master\AdminRoleMstController;
use App\Http\Controllers\Master\ApiRoleMstController;
use App\Http\Controllers\Master\DepartmentManagementMstController;
use App\Http\Controllers\Master\TranslationLanguageMstController;
use App\Http\Controllers\Management\BannerMgmtController;
use App\Http\Controllers\Management\CategoryMgmtController;
use App\Http\Controllers\Management\CategorySkillMgmtController;
use App\Http\Controllers\Management\MediaMgmtController;
use App\Http\Controllers\Management\SettingLinkMgmtController;
use App\Http\Controllers\Management\SkillDescriptionMgmtController;
use App\Http\Controllers\Management\SkillMgmtController;
use App\Http\Controllers\Management\SliderMgmtController;
use App\Http\Controllers\Management\SocialMgmtController;
use App\Http\Controllers\Management\UserMgmtController;
use App\Http\Controllers\History\Master\AdminMstHistController;
use App\Http\Controllers\History\Master\ApiMstHistController;
use App\Http\Controllers\History\Master\DepartmentMstHistController;
use App\Http\Controllers\History\Master\FeatureMstHistController;
use App\Http\Controllers\History\Master\LanguageMstHistController;
use App\Http\Controllers\History\Master\OriginalTranslatorMstHistController;
use App\Http\Controllers\History\Master\PolicyDepartmentMstHistController;
use App\Http\Controllers\History\Master\RoleMstHistController;
use App\Http\Controllers\History\Master\TranslationMstHistController;
use App\Http\Controllers\History\Management\BannerMgmtHistController;
use App\Http\Controllers\History\Management\CategoryMgmtHistController;
use App\Http\Controllers\History\Management\SettingLinkMgmtHistController;
use App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController;
use App\Http\Controllers\History\Management\SkillMgmtHistController;
use App\Http\Controllers\History\Management\SliderMgmtHistController;
use App\Http\Controllers\History\Management\SocialMgmtHistController;
use App\Http\Controllers\History\Management\UserMgmtHistController;

Route::prefix('admin')
  ->middleware(GenerateResponseMiddleware::class, TransactionMiddleware::class)
  ->group(function () {
    Route::prefix('credential')->group(function () {
      Route::post('login', [CredentialController::class, 'login']);
    });

    Route::middleware(AdminMiddleware::class)
      ->group(function () {
        Route::prefix('credential')->group(function () {
          Route::prefix('trust')->group(function () {
            Route::post('refresh-token', [CredentialController::class, 'refreshToken']);
            Route::post('logout', [CredentialController::class, 'logout']);
          });
          Route::get('me', [CredentialController::class, 'me']);
        });

        // ============================================================
        // MASTER DATA ROUTES
        // ============================================================

        // Admin Master
        Route::get('admin-mst/list', [AdminMstController::class, 'list']);
        Route::post('admin-mst/store', [AdminMstController::class, 'store']);
        Route::put('admin-mst/update/{id}', [AdminMstController::class, 'update']);
        Route::delete('admin-mst/delete/{id}', [AdminMstController::class, 'delete']);

        // Role Master
        Route::get('role-mst/list', [RoleMstController::class, 'list']);
        Route::post('role-mst/store', [RoleMstController::class, 'store']);
        Route::put('role-mst/update/{id}', [RoleMstController::class, 'update']);
        Route::delete('role-mst/delete/{id}', [RoleMstController::class, 'delete']);

        // Department Master
        Route::get('department-mst/list', [DepartmentMstController::class, 'list']);
        Route::post('department-mst/store', [DepartmentMstController::class, 'store']);
        Route::put('department-mst/update/{id}', [DepartmentMstController::class, 'update']);
        Route::delete('department-mst/delete/{id}', [DepartmentMstController::class, 'delete']);

        // Feature Master
        Route::get('feature-mst/list', [FeatureMstController::class, 'list']);
        Route::post('feature-mst/store', [FeatureMstController::class, 'store']);
        Route::put('feature-mst/update/{id}', [FeatureMstController::class, 'update']);
        Route::delete('feature-mst/delete/{id}', [FeatureMstController::class, 'delete']);

        // API Master
        Route::get('api-mst/list', [ApiMstController::class, 'list']);
        Route::post('api-mst/store', [ApiMstController::class, 'store']);
        Route::put('api-mst/update/{id}', [ApiMstController::class, 'update']);
        Route::delete('api-mst/delete/{id}', [ApiMstController::class, 'delete']);

        // Language Master
        Route::get('language-mst/list', [LanguageMstController::class, 'list']);
        Route::post('language-mst/store', [LanguageMstController::class, 'store']);
        Route::put('language-mst/update/{id}', [LanguageMstController::class, 'update']);
        Route::delete('language-mst/delete/{id}', [LanguageMstController::class, 'delete']);

        // Translation Master
        Route::get('translation-mst/list', [TranslationMstController::class, 'list']);
        Route::post('translation-mst/store', [TranslationMstController::class, 'store']);
        Route::put('translation-mst/update/{id}', [TranslationMstController::class, 'update']);
        Route::delete('translation-mst/delete/{id}', [TranslationMstController::class, 'delete']);

        // Token Master
        Route::get('token-mst/list', [TokenMstController::class, 'list']);
        Route::post('token-mst/store', [TokenMstController::class, 'store']);
        Route::put('token-mst/update/{id}', [TokenMstController::class, 'update']);
        Route::delete('token-mst/delete/{id}', [TokenMstController::class, 'delete']);

        // Policy Department Master
        Route::get('policy-department-mst/list', [PolicyDepartmentMstController::class, 'list']);
        Route::post('policy-department-mst/store', [PolicyDepartmentMstController::class, 'store']);
        Route::put('policy-department-mst/update/{id}', [PolicyDepartmentMstController::class, 'update']);
        Route::delete('policy-department-mst/delete/{id}', [PolicyDepartmentMstController::class, 'delete']);

        // Original Translator Master
        Route::get('original-translator-mst/list', [OriginalTranslatorMstController::class, 'list']);
        Route::post('original-translator-mst/store', [OriginalTranslatorMstController::class, 'store']);
        Route::put('original-translator-mst/update/{id}', [OriginalTranslatorMstController::class, 'update']);
        Route::delete('original-translator-mst/delete/{id}', [OriginalTranslatorMstController::class, 'delete']);

        // ============================================================
        // JUNCTION TABLES (Many-to-Many Relationships)
        // ============================================================

        // Admin-Department Junction
        Route::get('admin-department-mst/list', [AdminDepartmentMstController::class, 'list']);
        Route::put('admin-department-mst/update', [AdminDepartmentMstController::class, 'update']);

        // Admin-Role Junction
        Route::get('admin-role-mst/list', [AdminRoleMstController::class, 'list']);
        Route::put('admin-role-mst/update', [AdminRoleMstController::class, 'update']);

        // API-Role Junction
        Route::get('api-role-mst/list', [ApiRoleMstController::class, 'list']);
        Route::put('api-role-mst/update', [ApiRoleMstController::class, 'update']);

        // Department-Management Junction
        Route::get('department-management-mst/list', [DepartmentManagementMstController::class, 'list']);
        Route::put('department-management-mst/update', [DepartmentManagementMstController::class, 'update']);

        // Translation-Language Junction
        Route::get('translation-language-mst/list', [TranslationLanguageMstController::class, 'list']);
        Route::put('translation-language-mst/update', [TranslationLanguageMstController::class, 'update']);

        // Category-Skill Junction
        Route::get('category-skill-mgmt/list', [CategorySkillMgmtController::class, 'list']);
        Route::put('category-skill-mgmt/update', [CategorySkillMgmtController::class, 'update']);

        // ============================================================
        // MANAGEMENT DATA ROUTES
        // ============================================================

        // Banner Management
        Route::get('banner-mgmt/list', [BannerMgmtController::class, 'list']);
        Route::post('banner-mgmt/store', [BannerMgmtController::class, 'store']);
        Route::put('banner-mgmt/update/{id}', [BannerMgmtController::class, 'update']);
        Route::delete('banner-mgmt/delete/{id}', [BannerMgmtController::class, 'delete']);

        // Category Management
        Route::get('category-mgmt/list', [CategoryMgmtController::class, 'list']);
        Route::post('category-mgmt/store', [CategoryMgmtController::class, 'store']);
        Route::put('category-mgmt/update/{id}', [CategoryMgmtController::class, 'update']);
        Route::delete('category-mgmt/delete/{id}', [CategoryMgmtController::class, 'delete']);

        // Skill Management
        Route::get('skill-mgmt/list', [SkillMgmtController::class, 'list']);
        Route::post('skill-mgmt/store', [SkillMgmtController::class, 'store']);
        Route::put('skill-mgmt/update/{id}', [SkillMgmtController::class, 'update']);
        Route::delete('skill-mgmt/delete/{id}', [SkillMgmtController::class, 'delete']);

        // Skill Description Management
        Route::get('skill-description-mgmt/list', [SkillDescriptionMgmtController::class, 'list']);
        Route::post('skill-description-mgmt/store', [SkillDescriptionMgmtController::class, 'store']);
        Route::put('skill-description-mgmt/update/{id}', [SkillDescriptionMgmtController::class, 'update']);
        Route::delete('skill-description-mgmt/delete/{id}', [SkillDescriptionMgmtController::class, 'delete']);

        // Slider Management
        Route::get('slider-mgmt/list', [SliderMgmtController::class, 'list']);
        Route::post('slider-mgmt/store', [SliderMgmtController::class, 'store']);
        Route::put('slider-mgmt/update/{id}', [SliderMgmtController::class, 'update']);
        Route::delete('slider-mgmt/delete/{id}', [SliderMgmtController::class, 'delete']);

        // Social Management
        Route::get('social-mgmt/list', [SocialMgmtController::class, 'list']);
        Route::post('social-mgmt/store', [SocialMgmtController::class, 'store']);
        Route::put('social-mgmt/update/{id}', [SocialMgmtController::class, 'update']);
        Route::delete('social-mgmt/delete/{id}', [SocialMgmtController::class, 'delete']);

        // User Management
        Route::get('user-mgmt/list', [UserMgmtController::class, 'list']);
        Route::post('user-mgmt/store', [UserMgmtController::class, 'store']);
        Route::put('user-mgmt/update/{id}', [UserMgmtController::class, 'update']);
        Route::delete('user-mgmt/delete/{id}', [UserMgmtController::class, 'delete']);

        // Setting Link Management
        Route::get('setting-link-mgmt/list', [SettingLinkMgmtController::class, 'list']);
        Route::post('setting-link-mgmt/store', [SettingLinkMgmtController::class, 'store']);
        Route::put('setting-link-mgmt/update/{id}', [SettingLinkMgmtController::class, 'update']);
        Route::delete('setting-link-mgmt/delete/{id}', [SettingLinkMgmtController::class, 'delete']);

        // Media Management (MinIO-based File Manager)
        Route::get('media-mgmt/list', [MediaMgmtController::class, 'list']);
        Route::post('media-mgmt/store', [MediaMgmtController::class, 'store']);
        Route::put('media-mgmt/update/{id}', [MediaMgmtController::class, 'update']);
        Route::delete('media-mgmt/delete/{id}', [MediaMgmtController::class, 'delete']);



        // ============================================================
        // HISTORY ROUTES (Audit Trail)
        // ============================================================

        // Admin Master History
        Route::get('admin-mst-hist/list', [AdminMstHistController::class, 'list']);
        Route::post('admin-mst-hist/store', [AdminMstHistController::class, 'store']);
        Route::put('admin-mst-hist/update/{id}', [AdminMstHistController::class, 'update']);
        Route::delete('admin-mst-hist/delete/{id}', [AdminMstHistController::class, 'delete']);

        // API Master History
        Route::get('api-mst-hist/list', [ApiMstHistController::class, 'list']);
        Route::post('api-mst-hist/store', [ApiMstHistController::class, 'store']);
        Route::put('api-mst-hist/update/{id}', [ApiMstHistController::class, 'update']);
        Route::delete('api-mst-hist/delete/{id}', [ApiMstHistController::class, 'delete']);

        // Department Master History
        Route::get('department-mst-hist/list', [DepartmentMstHistController::class, 'list']);
        Route::post('department-mst-hist/store', [DepartmentMstHistController::class, 'store']);
        Route::put('department-mst-hist/update/{id}', [DepartmentMstHistController::class, 'update']);
        Route::delete('department-mst-hist/delete/{id}', [DepartmentMstHistController::class, 'delete']);

        // Feature Master History
        Route::get('feature-mst-hist/list', [FeatureMstHistController::class, 'list']);
        Route::post('feature-mst-hist/store', [FeatureMstHistController::class, 'store']);
        Route::put('feature-mst-hist/update/{id}', [FeatureMstHistController::class, 'update']);
        Route::delete('feature-mst-hist/delete/{id}', [FeatureMstHistController::class, 'delete']);

        // Language Master History
        Route::get('language-mst-hist/list', [LanguageMstHistController::class, 'list']);
        Route::post('language-mst-hist/store', [LanguageMstHistController::class, 'store']);
        Route::put('language-mst-hist/update/{id}', [LanguageMstHistController::class, 'update']);
        Route::delete('language-mst-hist/delete/{id}', [LanguageMstHistController::class, 'delete']);

        // Original Translator Master History
        Route::get('original-translator-mst-hist/list', [OriginalTranslatorMstHistController::class, 'list']);
        Route::post('original-translator-mst-hist/store', [OriginalTranslatorMstHistController::class, 'store']);
        Route::put('original-translator-mst-hist/update/{id}', [OriginalTranslatorMstHistController::class, 'update']);
        Route::delete('original-translator-mst-hist/delete/{id}', [OriginalTranslatorMstHistController::class, 'delete']);

        // Policy Department Master History
        Route::get('policy-department-mst-hist/list', [PolicyDepartmentMstHistController::class, 'list']);
        Route::post('policy-department-mst-hist/store', [PolicyDepartmentMstHistController::class, 'store']);
        Route::put('policy-department-mst-hist/update/{id}', [PolicyDepartmentMstHistController::class, 'update']);
        Route::delete('policy-department-mst-hist/delete/{id}', [PolicyDepartmentMstHistController::class, 'delete']);

        // Role Master History
        Route::get('role-mst-hist/list', [RoleMstHistController::class, 'list']);
        Route::post('role-mst-hist/store', [RoleMstHistController::class, 'store']);
        Route::put('role-mst-hist/update/{id}', [RoleMstHistController::class, 'update']);
        Route::delete('role-mst-hist/delete/{id}', [RoleMstHistController::class, 'delete']);

        // Translation Master History
        Route::get('translation-mst-hist/list', [TranslationMstHistController::class, 'list']);
        Route::post('translation-mst-hist/store', [TranslationMstHistController::class, 'store']);
        Route::put('translation-mst-hist/update/{id}', [TranslationMstHistController::class, 'update']);
        Route::delete('translation-mst-hist/delete/{id}', [TranslationMstHistController::class, 'delete']);

        // Banner Management History
        Route::get('banner-mgmt-hist/list', [BannerMgmtHistController::class, 'list']);
        Route::post('banner-mgmt-hist/store', [BannerMgmtHistController::class, 'store']);
        Route::put('banner-mgmt-hist/update/{id}', [BannerMgmtHistController::class, 'update']);
        Route::delete('banner-mgmt-hist/delete/{id}', [BannerMgmtHistController::class, 'delete']);

        // Category Management History
        Route::get('category-mgmt-hist/list', [CategoryMgmtHistController::class, 'list']);
        Route::post('category-mgmt-hist/store', [CategoryMgmtHistController::class, 'store']);
        Route::put('category-mgmt-hist/update/{id}', [CategoryMgmtHistController::class, 'update']);
        Route::delete('category-mgmt-hist/delete/{id}', [CategoryMgmtHistController::class, 'delete']);

        // Setting Link Management History
        Route::get('setting-link-mgmt-hist/list', [SettingLinkMgmtHistController::class, 'list']);
        Route::post('setting-link-mgmt-hist/store', [SettingLinkMgmtHistController::class, 'store']);
        Route::put('setting-link-mgmt-hist/update/{id}', [SettingLinkMgmtHistController::class, 'update']);
        Route::delete('setting-link-mgmt-hist/delete/{id}', [SettingLinkMgmtHistController::class, 'delete']);

        // Skill Description Management History
        Route::get('skill-description-mgmt-hist/list', [SkillDescriptionMgmtHistController::class, 'list']);
        Route::post('skill-description-mgmt-hist/store', [SkillDescriptionMgmtHistController::class, 'store']);
        Route::put('skill-description-mgmt-hist/update/{id}', [SkillDescriptionMgmtHistController::class, 'update']);
        Route::delete('skill-description-mgmt-hist/delete/{id}', [SkillDescriptionMgmtHistController::class, 'delete']);

        // Skill Management History
        Route::get('skill-mgmt-hist/list', [SkillMgmtHistController::class, 'list']);
        Route::post('skill-mgmt-hist/store', [SkillMgmtHistController::class, 'store']);
        Route::put('skill-mgmt-hist/update/{id}', [SkillMgmtHistController::class, 'update']);
        Route::delete('skill-mgmt-hist/delete/{id}', [SkillMgmtHistController::class, 'delete']);

        // Slider Management History
        Route::get('slider-mgmt-hist/list', [SliderMgmtHistController::class, 'list']);
        Route::post('slider-mgmt-hist/store', [SliderMgmtHistController::class, 'store']);
        Route::put('slider-mgmt-hist/update/{id}', [SliderMgmtHistController::class, 'update']);
        Route::delete('slider-mgmt-hist/delete/{id}', [SliderMgmtHistController::class, 'delete']);

        // Social Management History
        Route::get('social-mgmt-hist/list', [SocialMgmtHistController::class, 'list']);
        Route::post('social-mgmt-hist/store', [SocialMgmtHistController::class, 'store']);
        Route::put('social-mgmt-hist/update/{id}', [SocialMgmtHistController::class, 'update']);
        Route::delete('social-mgmt-hist/delete/{id}', [SocialMgmtHistController::class, 'delete']);

        // User Management History
        Route::get('user-mgmt-hist/list', [UserMgmtHistController::class, 'list']);
        Route::post('user-mgmt-hist/store', [UserMgmtHistController::class, 'store']);
        Route::put('user-mgmt-hist/update/{id}', [UserMgmtHistController::class, 'update']);
        Route::delete('user-mgmt-hist/delete/{id}', [UserMgmtHistController::class, 'delete']);
      });
  });
