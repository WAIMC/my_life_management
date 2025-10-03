<?php

use Illuminate\Support\Facades\Route;

// Routes for admin_department_mst
Route::get('admin-department-mst', [\App\Http\Controllers\Master\AdminDepartmentMstController::class, 'list']);
Route::put('admin-department-mst', [\App\Http\Controllers\Master\AdminDepartmentMstController::class, 'update']);  // or put if preferred

// Routes for admin_mst
Route::apiResource('admin-mst', \App\Http\Controllers\Master\AdminMstController::class);

// Routes for admin_mst_hist
Route::apiResource('admin-mst-hist', \App\Http\Controllers\History\Master\AdminMstHistController::class);

// Routes for admin_role_mst
Route::get('admin-role-mst', [\App\Http\Controllers\Master\AdminRoleMstController::class, 'list']);
Route::put('admin-role-mst', [\App\Http\Controllers\Master\AdminRoleMstController::class, 'update']);  // or put if preferred

// Routes for api_mst
Route::apiResource('api-mst', \App\Http\Controllers\Master\ApiMstController::class);

// Routes for api_role_mst
Route::get('api-role-mst', [\App\Http\Controllers\Master\ApiRoleMstController::class, 'list']);
Route::put('api-role-mst', [\App\Http\Controllers\Master\ApiRoleMstController::class, 'update']);  // or put if preferred

// Routes for feature_mst
Route::apiResource('feature-mst', \App\Http\Controllers\Master\FeatureMstController::class);

// Routes for role_mst
Route::apiResource('role-mst', \App\Http\Controllers\Master\RoleMstController::class);

// Routes for department_management_mst
Route::get('department-management-mst', [\App\Http\Controllers\Master\DepartmentManagementMstController::class, 'list']);
Route::put('department-management-mst', [\App\Http\Controllers\Master\DepartmentManagementMstController::class, 'update']);  // or put if preferred

// Routes for department_mst
Route::apiResource('department-mst', \App\Http\Controllers\Master\DepartmentMstController::class);

// Routes for policy_department_mst
Route::apiResource('policy-department-mst', \App\Http\Controllers\Master\PolicyDepartmentMstController::class);

// Routes for api_mst_hist
Route::apiResource('api-mst-hist', \App\Http\Controllers\History\Master\ApiMstHistController::class);

// Routes for banner_mgmt
Route::apiResource('banner-mgmt', \App\Http\Controllers\Management\BannerMgmtController::class);

// Routes for banner_mgmt_hist
Route::apiResource('banner-mgmt-hist', \App\Http\Controllers\History\Management\BannerMgmtHistController::class);

// Routes for category_mgmt
Route::apiResource('category-mgmt', \App\Http\Controllers\Management\CategoryMgmtController::class);

// Routes for category_mgmt_hist
Route::apiResource('category-mgmt-hist', \App\Http\Controllers\History\Management\CategoryMgmtHistController::class);

// Routes for category_skill_mgmt
Route::get('category-skill-mgmt', [\App\Http\Controllers\Management\CategorySkillMgmtController::class, 'list']);
Route::put('category-skill-mgmt', [\App\Http\Controllers\Management\CategorySkillMgmtController::class, 'update']);  // or put if preferred

// Routes for department_mst_hist
Route::apiResource('department-mst-hist', \App\Http\Controllers\History\Master\DepartmentMstHistController::class);

// Routes for feature_mst_hist
Route::apiResource('feature-mst-hist', \App\Http\Controllers\History\Master\FeatureMstHistController::class);

// Routes for language_mst
Route::apiResource('language-mst', \App\Http\Controllers\Master\LanguageMstController::class);

// Routes for language_mst_hist
Route::apiResource('language-mst-hist', \App\Http\Controllers\History\Master\LanguageMstHistController::class);

// Routes for original_translator_mst
Route::apiResource('original-translator-mst', \App\Http\Controllers\Master\OriginalTranslatorMstController::class);

// Routes for original_translator_mst_hist
Route::apiResource('original-translator-mst-hist', \App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class);

// Routes for policy_department_mst_hist
Route::apiResource('policy-department-mst-hist', \App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class);

// Routes for role_mst_hist
Route::apiResource('role-mst-hist', \App\Http\Controllers\History\Master\RoleMstHistController::class);

// Routes for setting_link_mgmt
Route::apiResource('setting-link-mgmt', \App\Http\Controllers\Management\SettingLinkMgmtController::class);

// Routes for setting_link_mgmt_hist
Route::apiResource('setting-link-mgmt-hist', \App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class);

// Routes for skill_description_mgmt
Route::apiResource('skill-description-mgmt', \App\Http\Controllers\Management\SkillDescriptionMgmtController::class);

// Routes for skill_description_mgmt_hist
Route::apiResource('skill-description-mgmt-hist', \App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class);

// Routes for skill_mgmt
Route::apiResource('skill-mgmt', \App\Http\Controllers\Management\SkillMgmtController::class);

// Routes for skill_mgmt_hist
Route::apiResource('skill-mgmt-hist', \App\Http\Controllers\History\Management\SkillMgmtHistController::class);

// Routes for slider_mgmt
Route::apiResource('slider-mgmt', \App\Http\Controllers\Management\SliderMgmtController::class);

// Routes for slider_mgmt_hist
Route::apiResource('slider-mgmt-hist', \App\Http\Controllers\History\Management\SliderMgmtHistController::class);

// Routes for social_mgmt
Route::apiResource('social-mgmt', \App\Http\Controllers\Management\SocialMgmtController::class);

// Routes for social_mgmt_hist
Route::apiResource('social-mgmt-hist', \App\Http\Controllers\History\Management\SocialMgmtHistController::class);

// Routes for translation_language_mst
Route::get('translation-language-mst', [\App\Http\Controllers\Master\TranslationLanguageMstController::class, 'list']);
Route::put('translation-language-mst', [\App\Http\Controllers\Master\TranslationLanguageMstController::class, 'update']);  // or put if preferred

// Routes for translation_mst
Route::apiResource('translation-mst', \App\Http\Controllers\Master\TranslationMstController::class);

// Routes for translation_mst_hist
Route::apiResource('translation-mst-hist', \App\Http\Controllers\History\Master\TranslationMstHistController::class);

// Routes for user_mgmt
Route::apiResource('user-mgmt', \App\Http\Controllers\Management\UserMgmtController::class);

// Routes for user_mgmt_hist
Route::apiResource('user-mgmt-hist', \App\Http\Controllers\History\Management\UserMgmtHistController::class);

