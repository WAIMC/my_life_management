<?php

use App\Http\Controllers\Custom\CredentialController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GenerateResponseMiddleware;
use App\Http\Middleware\TransactionMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(GenerateResponseMiddleware::class)
    ->group(function () {
        Route::prefix('account')->group(function () {
            Route::post('login', [CredentialController::class, 'login']);
            Route::post('refresh-token', [CredentialController::class, 'refreshToken']);
        });

        // DISABLED: Authentication middleware
        // ->middleware(AdminMiddleware::class)
        Route::middleware(TransactionMiddleware::class)
            ->group(function () {
                Route::post('account/logout', [CredentialController::class, 'logout']);
                Route::get('account/me', [CredentialController::class, 'me']);

                // ============================================================
                // MASTER DATA ROUTES
                // ============================================================

                // Admin Master
                Route::get('admin-mst/list', [\App\Http\Controllers\Master\AdminMstController::class, 'list']);
                Route::post('admin-mst/store', [\App\Http\Controllers\Master\AdminMstController::class, 'store']);
                Route::put('admin-mst/update/{id}', [\App\Http\Controllers\Master\AdminMstController::class, 'update']);
                Route::delete('admin-mst/delete/{id}', [\App\Http\Controllers\Master\AdminMstController::class, 'delete']);

                // Role Master
                Route::get('role-mst/list', [\App\Http\Controllers\Master\RoleMstController::class, 'list']);
                Route::post('role-mst/store', [\App\Http\Controllers\Master\RoleMstController::class, 'store']);
                Route::put('role-mst/update/{id}', [\App\Http\Controllers\Master\RoleMstController::class, 'update']);
                Route::delete('role-mst/delete/{id}', [\App\Http\Controllers\Master\RoleMstController::class, 'delete']);

                // Department Master
                Route::get('department-mst/list', [\App\Http\Controllers\Master\DepartmentMstController::class, 'list']);
                Route::post('department-mst/store', [\App\Http\Controllers\Master\DepartmentMstController::class, 'store']);
                Route::put('department-mst/update/{id}', [\App\Http\Controllers\Master\DepartmentMstController::class, 'update']);
                Route::delete('department-mst/delete/{id}', [\App\Http\Controllers\Master\DepartmentMstController::class, 'delete']);

                // Feature Master
                Route::get('feature-mst/list', [\App\Http\Controllers\Master\FeatureMstController::class, 'list']);
                Route::post('feature-mst/store', [\App\Http\Controllers\Master\FeatureMstController::class, 'store']);
                Route::put('feature-mst/update/{id}', [\App\Http\Controllers\Master\FeatureMstController::class, 'update']);
                Route::delete('feature-mst/delete/{id}', [\App\Http\Controllers\Master\FeatureMstController::class, 'delete']);

                // API Master
                Route::get('api-mst/list', [\App\Http\Controllers\Master\ApiMstController::class, 'list']);
                Route::post('api-mst/store', [\App\Http\Controllers\Master\ApiMstController::class, 'store']);
                Route::put('api-mst/update/{id}', [\App\Http\Controllers\Master\ApiMstController::class, 'update']);
                Route::delete('api-mst/delete/{id}', [\App\Http\Controllers\Master\ApiMstController::class, 'delete']);

                // Language Master
                Route::get('language-mst/list', [\App\Http\Controllers\Master\LanguageMstController::class, 'list']);
                Route::post('language-mst/store', [\App\Http\Controllers\Master\LanguageMstController::class, 'store']);
                Route::put('language-mst/update/{id}', [\App\Http\Controllers\Master\LanguageMstController::class, 'update']);
                Route::delete('language-mst/delete/{id}', [\App\Http\Controllers\Master\LanguageMstController::class, 'delete']);

                // Translation Master
                Route::get('translation-mst/list', [\App\Http\Controllers\Master\TranslationMstController::class, 'list']);
                Route::post('translation-mst/store', [\App\Http\Controllers\Master\TranslationMstController::class, 'store']);
                Route::put('translation-mst/update/{id}', [\App\Http\Controllers\Master\TranslationMstController::class, 'update']);
                Route::delete('translation-mst/delete/{id}', [\App\Http\Controllers\Master\TranslationMstController::class, 'delete']);

                // Token Master
                Route::get('token-mst/list', [\App\Http\Controllers\Master\TokenMstController::class, 'list']);
                Route::post('token-mst/store', [\App\Http\Controllers\Master\TokenMstController::class, 'store']);
                Route::put('token-mst/update/{id}', [\App\Http\Controllers\Master\TokenMstController::class, 'update']);
                Route::delete('token-mst/delete/{id}', [\App\Http\Controllers\Master\TokenMstController::class, 'delete']);

                // Policy Department Master
                Route::get('policy-department-mst/list', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'list']);
                Route::post('policy-department-mst/store', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'store']);
                Route::put('policy-department-mst/update/{id}', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'update']);
                Route::delete('policy-department-mst/delete/{id}', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'delete']);

                // Original Translator Master
                Route::get('original-translator-mst/list', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'list']);
                Route::post('original-translator-mst/store', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'store']);
                Route::put('original-translator-mst/update/{id}', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'update']);
                Route::delete('original-translator-mst/delete/{id}', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'delete']);

                // ============================================================
                // JUNCTION TABLES (Many-to-Many Relationships)
                // ============================================================

                // Admin-Department Junction
                Route::get('admin-department-mst/list', [\App\Http\Controllers\Master\AdminDepartmentMstController::class, 'list']);
                Route::put('admin-department-mst/update', [\App\Http\Controllers\Master\AdminDepartmentMstController::class, 'update']);

                // Admin-Role Junction
                Route::get('admin-role-mst/list', [\App\Http\Controllers\Master\AdminRoleMstController::class, 'list']);
                Route::put('admin-role-mst/update', [\App\Http\Controllers\Master\AdminRoleMstController::class, 'update']);

                // API-Role Junction
                Route::get('api-role-mst/list', [\App\Http\Controllers\Master\ApiRoleMstController::class, 'list']);
                Route::put('api-role-mst/update', [\App\Http\Controllers\Master\ApiRoleMstController::class, 'update']);

                // Department-Management Junction
                Route::get('department-management-mst/list', [\App\Http\Controllers\Master\DepartmentManagementMstController::class, 'list']);
                Route::put('department-management-mst/update', [\App\Http\Controllers\Master\DepartmentManagementMstController::class, 'update']);

                // Translation-Language Junction
                Route::get('translation-language-mst/list', [\App\Http\Controllers\Master\TranslationLanguageMstController::class, 'list']);
                Route::put('translation-language-mst/update', [\App\Http\Controllers\Master\TranslationLanguageMstController::class, 'update']);

                // Category-Skill Junction
                Route::get('category-skill-mgmt/list', [\App\Http\Controllers\Management\CategorySkillMgmtController::class, 'list']);
                Route::put('category-skill-mgmt/update', [\App\Http\Controllers\Management\CategorySkillMgmtController::class, 'update']);

                // ============================================================
                // MANAGEMENT DATA ROUTES
                // ============================================================

                // Banner Management
                Route::get('banner-mgmt/list', [\App\Http\Controllers\Management\BannerMgmtController::class, 'list']);
                Route::post('banner-mgmt/store', [\App\Http\Controllers\Management\BannerMgmtController::class, 'store']);
                Route::put('banner-mgmt/update/{id}', [\App\Http\Controllers\Management\BannerMgmtController::class, 'update']);
                Route::delete('banner-mgmt/delete/{id}', [\App\Http\Controllers\Management\BannerMgmtController::class, 'delete']);

                // Category Management
                Route::get('category-mgmt/list', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'list']);
                Route::post('category-mgmt/store', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'store']);
                Route::put('category-mgmt/update/{id}', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'update']);
                Route::delete('category-mgmt/delete/{id}', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'delete']);

                // Skill Management
                Route::get('skill-mgmt/list', [\App\Http\Controllers\Management\SkillMgmtController::class, 'list']);
                Route::post('skill-mgmt/store', [\App\Http\Controllers\Management\SkillMgmtController::class, 'store']);
                Route::put('skill-mgmt/update/{id}', [\App\Http\Controllers\Management\SkillMgmtController::class, 'update']);
                Route::delete('skill-mgmt/delete/{id}', [\App\Http\Controllers\Management\SkillMgmtController::class, 'delete']);

                // Skill Description Management
                Route::get('skill-description-mgmt/list', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'list']);
                Route::post('skill-description-mgmt/store', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'store']);
                Route::put('skill-description-mgmt/update/{id}', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'update']);
                Route::delete('skill-description-mgmt/delete/{id}', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'delete']);

                // Slider Management
                Route::get('slider-mgmt/list', [\App\Http\Controllers\Management\SliderMgmtController::class, 'list']);
                Route::post('slider-mgmt/store', [\App\Http\Controllers\Management\SliderMgmtController::class, 'store']);
                Route::put('slider-mgmt/update/{id}', [\App\Http\Controllers\Management\SliderMgmtController::class, 'update']);
                Route::delete('slider-mgmt/delete/{id}', [\App\Http\Controllers\Management\SliderMgmtController::class, 'delete']);

                // Social Management
                Route::get('social-mgmt/list', [\App\Http\Controllers\Management\SocialMgmtController::class, 'list']);
                Route::post('social-mgmt/store', [\App\Http\Controllers\Management\SocialMgmtController::class, 'store']);
                Route::put('social-mgmt/update/{id}', [\App\Http\Controllers\Management\SocialMgmtController::class, 'update']);
                Route::delete('social-mgmt/delete/{id}', [\App\Http\Controllers\Management\SocialMgmtController::class, 'delete']);

                // User Management
                Route::get('user-mgmt/list', [\App\Http\Controllers\Management\UserMgmtController::class, 'list']);
                Route::post('user-mgmt/store', [\App\Http\Controllers\Management\UserMgmtController::class, 'store']);
                Route::put('user-mgmt/update/{id}', [\App\Http\Controllers\Management\UserMgmtController::class, 'update']);
                Route::delete('user-mgmt/delete/{id}', [\App\Http\Controllers\Management\UserMgmtController::class, 'delete']);

                // Setting Link Management
                Route::get('setting-link-mgmt/list', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'list']);
                Route::post('setting-link-mgmt/store', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'store']);
                Route::put('setting-link-mgmt/update/{id}', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'update']);
                Route::delete('setting-link-mgmt/delete/{id}', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'delete']);

                // Media File Management
                Route::post('media-files/upload', [\App\Http\Controllers\Management\MediaFileController::class, 'upload']);
                Route::get('media-files', [\App\Http\Controllers\Management\MediaFileController::class, 'list']);
                Route::get('media-files/{id}', [\App\Http\Controllers\Management\MediaFileController::class, 'show']);
                Route::get('media-files/{id}/view', [\App\Http\Controllers\Management\MediaFileController::class, 'view'])->name('api.media-files.view');
                Route::get('media-files/{id}/download', [\App\Http\Controllers\Management\MediaFileController::class, 'download'])->name('api.media-files.download');
                Route::put('media-files/{id}/rename', [\App\Http\Controllers\Management\MediaFileController::class, 'rename']);
                Route::put('media-files/{id}/move', [\App\Http\Controllers\Management\MediaFileController::class, 'move']);
                Route::delete('media-files/delete', [\App\Http\Controllers\Management\MediaFileController::class, 'delete']);
                Route::post('media-files/folders', [\App\Http\Controllers\Management\MediaFileController::class, 'createFolder']);
                Route::get('media-files/folders', [\App\Http\Controllers\Management\MediaFileController::class, 'listFolders']);
                Route::post('media-files/copy', [\App\Http\Controllers\Management\MediaFileController::class, 'copy']);

                // Google Drive Configuration Management
                Route::post('google-drive-configs/upload', [\App\Http\Controllers\Management\GoogleDriveConfigController::class, 'upload']);
                Route::get('google-drive-configs', [\App\Http\Controllers\Management\GoogleDriveConfigController::class, 'list']);
                Route::get('google-drive-configs/active', [\App\Http\Controllers\Management\GoogleDriveConfigController::class, 'getActive']);
                Route::put('google-drive-configs/{id}/activate', [\App\Http\Controllers\Management\GoogleDriveConfigController::class, 'activate']);
                Route::delete('google-drive-configs/delete', [\App\Http\Controllers\Management\GoogleDriveConfigController::class, 'delete']);

                // ============================================================
                // HISTORY ROUTES (Audit Trail)
                // ============================================================

                // Admin Master History
                Route::get('admin-mst-hist/list', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'list']);
                Route::post('admin-mst-hist/store', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'store']);
                Route::put('admin-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'update']);
                Route::delete('admin-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'delete']);

                // API Master History
                Route::get('api-mst-hist/list', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'list']);
                Route::post('api-mst-hist/store', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'store']);
                Route::put('api-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'update']);
                Route::delete('api-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'delete']);

                // Department Master History
                Route::get('department-mst-hist/list', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'list']);
                Route::post('department-mst-hist/store', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'store']);
                Route::put('department-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'update']);
                Route::delete('department-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'delete']);

                // Feature Master History
                Route::get('feature-mst-hist/list', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'list']);
                Route::post('feature-mst-hist/store', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'store']);
                Route::put('feature-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'update']);
                Route::delete('feature-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'delete']);

                // Language Master History
                Route::get('language-mst-hist/list', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'list']);
                Route::post('language-mst-hist/store', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'store']);
                Route::put('language-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'update']);
                Route::delete('language-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'delete']);

                // Original Translator Master History
                Route::get('original-translator-mst-hist/list', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'list']);
                Route::post('original-translator-mst-hist/store', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'store']);
                Route::put('original-translator-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'update']);
                Route::delete('original-translator-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'delete']);

                // Policy Department Master History
                Route::get('policy-department-mst-hist/list', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'list']);
                Route::post('policy-department-mst-hist/store', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'store']);
                Route::put('policy-department-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'update']);
                Route::delete('policy-department-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'delete']);

                // Role Master History
                Route::get('role-mst-hist/list', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'list']);
                Route::post('role-mst-hist/store', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'store']);
                Route::put('role-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'update']);
                Route::delete('role-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'delete']);

                // Translation Master History
                Route::get('translation-mst-hist/list', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'list']);
                Route::post('translation-mst-hist/store', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'store']);
                Route::put('translation-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'update']);
                Route::delete('translation-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'delete']);

                // Banner Management History
                Route::get('banner-mgmt-hist/list', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'list']);
                Route::post('banner-mgmt-hist/store', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'store']);
                Route::put('banner-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'update']);
                Route::delete('banner-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'delete']);

                // Category Management History
                Route::get('category-mgmt-hist/list', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'list']);
                Route::post('category-mgmt-hist/store', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'store']);
                Route::put('category-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'update']);
                Route::delete('category-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'delete']);

                // Setting Link Management History
                Route::get('setting-link-mgmt-hist/list', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'list']);
                Route::post('setting-link-mgmt-hist/store', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'store']);
                Route::put('setting-link-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'update']);
                Route::delete('setting-link-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'delete']);

                // Skill Description Management History
                Route::get('skill-description-mgmt-hist/list', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'list']);
                Route::post('skill-description-mgmt-hist/store', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'store']);
                Route::put('skill-description-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'update']);
                Route::delete('skill-description-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'delete']);

                // Skill Management History
                Route::get('skill-mgmt-hist/list', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'list']);
                Route::post('skill-mgmt-hist/store', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'store']);
                Route::put('skill-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'update']);
                Route::delete('skill-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'delete']);

                // Slider Management History
                Route::get('slider-mgmt-hist/list', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'list']);
                Route::post('slider-mgmt-hist/store', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'store']);
                Route::put('slider-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'update']);
                Route::delete('slider-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'delete']);

                // Social Management History
                Route::get('social-mgmt-hist/list', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'list']);
                Route::post('social-mgmt-hist/store', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'store']);
                Route::put('social-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'update']);
                Route::delete('social-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'delete']);

                // User Management History
                Route::get('user-mgmt-hist/list', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'list']);
                Route::post('user-mgmt-hist/store', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'store']);
                Route::put('user-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'update']);
                Route::delete('user-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'delete']);
            });
    });
