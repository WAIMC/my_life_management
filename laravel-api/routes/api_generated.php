<?php

use Illuminate\Support\Facades\Route;

// Routes for admin_department_mst
Route::get('admin-department-mst/list', [\App\Http\Controllers\Master\AdminDepartmentMstController::class, 'list']);
Route::put('admin-department-mst/update', [\App\Http\Controllers\Master\AdminDepartmentMstController::class, 'update']);

// Routes for admin_mst
Route::get('admin-mst/list', [\App\Http\Controllers\Master\AdminMstController::class, 'list']);
Route::post('admin-mst/store', [\App\Http\Controllers\Master\AdminMstController::class, 'store']);
Route::put('admin-mst/update/{id}', [\App\Http\Controllers\Master\AdminMstController::class, 'update']);
Route::delete('admin-mst/delete/{id}', [\App\Http\Controllers\Master\AdminMstController::class, 'delete']);

// Routes for admin_mst_hist
Route::get('admin-mst-hist/list', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'list']);
Route::post('admin-mst-hist/store', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'store']);
Route::put('admin-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'update']);
Route::delete('admin-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\AdminMstHistController::class, 'delete']);

// Routes for admin_role_mst
Route::get('admin-role-mst/list', [\App\Http\Controllers\Master\AdminRoleMstController::class, 'list']);
Route::put('admin-role-mst/update', [\App\Http\Controllers\Master\AdminRoleMstController::class, 'update']);

// Routes for api_mst
Route::get('api-mst/list', [\App\Http\Controllers\Master\ApiMstController::class, 'list']);
Route::post('api-mst/store', [\App\Http\Controllers\Master\ApiMstController::class, 'store']);
Route::put('api-mst/update/{id}', [\App\Http\Controllers\Master\ApiMstController::class, 'update']);
Route::delete('api-mst/delete/{id}', [\App\Http\Controllers\Master\ApiMstController::class, 'delete']);

// Routes for api_role_mst
Route::get('api-role-mst/list', [\App\Http\Controllers\Master\ApiRoleMstController::class, 'list']);
Route::put('api-role-mst/update', [\App\Http\Controllers\Master\ApiRoleMstController::class, 'update']);

// Routes for feature_mst
Route::get('feature-mst/list', [\App\Http\Controllers\Master\FeatureMstController::class, 'list']);
Route::post('feature-mst/store', [\App\Http\Controllers\Master\FeatureMstController::class, 'store']);
Route::put('feature-mst/update/{id}', [\App\Http\Controllers\Master\FeatureMstController::class, 'update']);
Route::delete('feature-mst/delete/{id}', [\App\Http\Controllers\Master\FeatureMstController::class, 'delete']);

// Routes for role_mst
Route::get('role-mst/list', [\App\Http\Controllers\Master\RoleMstController::class, 'list']);
Route::post('role-mst/store', [\App\Http\Controllers\Master\RoleMstController::class, 'store']);
Route::put('role-mst/update/{id}', [\App\Http\Controllers\Master\RoleMstController::class, 'update']);
Route::delete('role-mst/delete/{id}', [\App\Http\Controllers\Master\RoleMstController::class, 'delete']);

// Routes for department_management_mst
Route::get('department-management-mst/list', [\App\Http\Controllers\Master\DepartmentManagementMstController::class, 'list']);
Route::put('department-management-mst/update', [\App\Http\Controllers\Master\DepartmentManagementMstController::class, 'update']);

// Routes for department_mst
Route::get('department-mst/list', [\App\Http\Controllers\Master\DepartmentMstController::class, 'list']);
Route::post('department-mst/store', [\App\Http\Controllers\Master\DepartmentMstController::class, 'store']);
Route::put('department-mst/update/{id}', [\App\Http\Controllers\Master\DepartmentMstController::class, 'update']);
Route::delete('department-mst/delete/{id}', [\App\Http\Controllers\Master\DepartmentMstController::class, 'delete']);

// Routes for policy_department_mst
Route::get('policy-department-mst/list', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'list']);
Route::post('policy-department-mst/store', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'store']);
Route::put('policy-department-mst/update/{id}', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'update']);
Route::delete('policy-department-mst/delete/{id}', [\App\Http\Controllers\Master\PolicyDepartmentMstController::class, 'delete']);

// Routes for api_mst_hist
Route::get('api-mst-hist/list', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'list']);
Route::post('api-mst-hist/store', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'store']);
Route::put('api-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'update']);
Route::delete('api-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\ApiMstHistController::class, 'delete']);

// Routes for banner_mgmt
Route::get('banner-mgmt/list', [\App\Http\Controllers\Management\BannerMgmtController::class, 'list']);
Route::post('banner-mgmt/store', [\App\Http\Controllers\Management\BannerMgmtController::class, 'store']);
Route::put('banner-mgmt/update/{id}', [\App\Http\Controllers\Management\BannerMgmtController::class, 'update']);
Route::delete('banner-mgmt/delete/{id}', [\App\Http\Controllers\Management\BannerMgmtController::class, 'delete']);

// Routes for banner_mgmt_hist
Route::get('banner-mgmt-hist/list', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'list']);
Route::post('banner-mgmt-hist/store', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'store']);
Route::put('banner-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'update']);
Route::delete('banner-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\BannerMgmtHistController::class, 'delete']);

// Routes for category_mgmt
Route::get('category-mgmt/list', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'list']);
Route::post('category-mgmt/store', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'store']);
Route::put('category-mgmt/update/{id}', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'update']);
Route::delete('category-mgmt/delete/{id}', [\App\Http\Controllers\Management\CategoryMgmtController::class, 'delete']);

// Routes for category_mgmt_hist
Route::get('category-mgmt-hist/list', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'list']);
Route::post('category-mgmt-hist/store', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'store']);
Route::put('category-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'update']);
Route::delete('category-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\CategoryMgmtHistController::class, 'delete']);

// Routes for category_skill_mgmt
Route::get('category-skill-mgmt/list', [\App\Http\Controllers\Management\CategorySkillMgmtController::class, 'list']);
Route::put('category-skill-mgmt/update', [\App\Http\Controllers\Management\CategorySkillMgmtController::class, 'update']);

// Routes for department_mst_hist
Route::get('department-mst-hist/list', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'list']);
Route::post('department-mst-hist/store', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'store']);
Route::put('department-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'update']);
Route::delete('department-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\DepartmentMstHistController::class, 'delete']);

// Routes for feature_mst_hist
Route::get('feature-mst-hist/list', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'list']);
Route::post('feature-mst-hist/store', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'store']);
Route::put('feature-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'update']);
Route::delete('feature-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\FeatureMstHistController::class, 'delete']);

// Routes for language_mst
Route::get('language-mst/list', [\App\Http\Controllers\Master\LanguageMstController::class, 'list']);
Route::post('language-mst/store', [\App\Http\Controllers\Master\LanguageMstController::class, 'store']);
Route::put('language-mst/update/{id}', [\App\Http\Controllers\Master\LanguageMstController::class, 'update']);
Route::delete('language-mst/delete/{id}', [\App\Http\Controllers\Master\LanguageMstController::class, 'delete']);

// Routes for language_mst_hist
Route::get('language-mst-hist/list', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'list']);
Route::post('language-mst-hist/store', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'store']);
Route::put('language-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'update']);
Route::delete('language-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\LanguageMstHistController::class, 'delete']);

// Routes for original_translator_mst
Route::get('original-translator-mst/list', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'list']);
Route::post('original-translator-mst/store', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'store']);
Route::put('original-translator-mst/update/{id}', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'update']);
Route::delete('original-translator-mst/delete/{id}', [\App\Http\Controllers\Master\OriginalTranslatorMstController::class, 'delete']);

// Routes for original_translator_mst_hist
Route::get('original-translator-mst-hist/list', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'list']);
Route::post('original-translator-mst-hist/store', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'store']);
Route::put('original-translator-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'update']);
Route::delete('original-translator-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\OriginalTranslatorMstHistController::class, 'delete']);

// Routes for policy_department_mst_hist
Route::get('policy-department-mst-hist/list', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'list']);
Route::post('policy-department-mst-hist/store', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'store']);
Route::put('policy-department-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'update']);
Route::delete('policy-department-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\PolicyDepartmentMstHistController::class, 'delete']);

// Routes for role_mst_hist
Route::get('role-mst-hist/list', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'list']);
Route::post('role-mst-hist/store', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'store']);
Route::put('role-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'update']);
Route::delete('role-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\RoleMstHistController::class, 'delete']);

// Routes for setting_link_mgmt
Route::get('setting-link-mgmt/list', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'list']);
Route::post('setting-link-mgmt/store', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'store']);
Route::put('setting-link-mgmt/update/{id}', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'update']);
Route::delete('setting-link-mgmt/delete/{id}', [\App\Http\Controllers\Management\SettingLinkMgmtController::class, 'delete']);

// Routes for setting_link_mgmt_hist
Route::get('setting-link-mgmt-hist/list', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'list']);
Route::post('setting-link-mgmt-hist/store', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'store']);
Route::put('setting-link-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'update']);
Route::delete('setting-link-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SettingLinkMgmtHistController::class, 'delete']);

// Routes for skill_description_mgmt
Route::get('skill-description-mgmt/list', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'list']);
Route::post('skill-description-mgmt/store', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'store']);
Route::put('skill-description-mgmt/update/{id}', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'update']);
Route::delete('skill-description-mgmt/delete/{id}', [\App\Http\Controllers\Management\SkillDescriptionMgmtController::class, 'delete']);

// Routes for skill_description_mgmt_hist
Route::get('skill-description-mgmt-hist/list', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'list']);
Route::post('skill-description-mgmt-hist/store', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'store']);
Route::put('skill-description-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'update']);
Route::delete('skill-description-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SkillDescriptionMgmtHistController::class, 'delete']);

// Routes for skill_mgmt
Route::get('skill-mgmt/list', [\App\Http\Controllers\Management\SkillMgmtController::class, 'list']);
Route::post('skill-mgmt/store', [\App\Http\Controllers\Management\SkillMgmtController::class, 'store']);
Route::put('skill-mgmt/update/{id}', [\App\Http\Controllers\Management\SkillMgmtController::class, 'update']);
Route::delete('skill-mgmt/delete/{id}', [\App\Http\Controllers\Management\SkillMgmtController::class, 'delete']);

// Routes for skill_mgmt_hist
Route::get('skill-mgmt-hist/list', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'list']);
Route::post('skill-mgmt-hist/store', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'store']);
Route::put('skill-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'update']);
Route::delete('skill-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SkillMgmtHistController::class, 'delete']);

// Routes for slider_mgmt
Route::get('slider-mgmt/list', [\App\Http\Controllers\Management\SliderMgmtController::class, 'list']);
Route::post('slider-mgmt/store', [\App\Http\Controllers\Management\SliderMgmtController::class, 'store']);
Route::put('slider-mgmt/update/{id}', [\App\Http\Controllers\Management\SliderMgmtController::class, 'update']);
Route::delete('slider-mgmt/delete/{id}', [\App\Http\Controllers\Management\SliderMgmtController::class, 'delete']);

// Routes for slider_mgmt_hist
Route::get('slider-mgmt-hist/list', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'list']);
Route::post('slider-mgmt-hist/store', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'store']);
Route::put('slider-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'update']);
Route::delete('slider-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SliderMgmtHistController::class, 'delete']);

// Routes for social_mgmt
Route::get('social-mgmt/list', [\App\Http\Controllers\Management\SocialMgmtController::class, 'list']);
Route::post('social-mgmt/store', [\App\Http\Controllers\Management\SocialMgmtController::class, 'store']);
Route::put('social-mgmt/update/{id}', [\App\Http\Controllers\Management\SocialMgmtController::class, 'update']);
Route::delete('social-mgmt/delete/{id}', [\App\Http\Controllers\Management\SocialMgmtController::class, 'delete']);

// Routes for social_mgmt_hist
Route::get('social-mgmt-hist/list', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'list']);
Route::post('social-mgmt-hist/store', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'store']);
Route::put('social-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'update']);
Route::delete('social-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\SocialMgmtHistController::class, 'delete']);

// Routes for translation_language_mst
Route::get('translation-language-mst/list', [\App\Http\Controllers\Master\TranslationLanguageMstController::class, 'list']);
Route::put('translation-language-mst/update', [\App\Http\Controllers\Master\TranslationLanguageMstController::class, 'update']);

// Routes for translation_mst
Route::get('translation-mst/list', [\App\Http\Controllers\Master\TranslationMstController::class, 'list']);
Route::post('translation-mst/store', [\App\Http\Controllers\Master\TranslationMstController::class, 'store']);
Route::put('translation-mst/update/{id}', [\App\Http\Controllers\Master\TranslationMstController::class, 'update']);
Route::delete('translation-mst/delete/{id}', [\App\Http\Controllers\Master\TranslationMstController::class, 'delete']);

// Routes for translation_mst_hist
Route::get('translation-mst-hist/list', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'list']);
Route::post('translation-mst-hist/store', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'store']);
Route::put('translation-mst-hist/update/{id}', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'update']);
Route::delete('translation-mst-hist/delete/{id}', [\App\Http\Controllers\History\Master\TranslationMstHistController::class, 'delete']);

// Routes for user_mgmt
Route::get('user-mgmt/list', [\App\Http\Controllers\Management\UserMgmtController::class, 'list']);
Route::post('user-mgmt/store', [\App\Http\Controllers\Management\UserMgmtController::class, 'store']);
Route::put('user-mgmt/update/{id}', [\App\Http\Controllers\Management\UserMgmtController::class, 'update']);
Route::delete('user-mgmt/delete/{id}', [\App\Http\Controllers\Management\UserMgmtController::class, 'delete']);

// Routes for user_mgmt_hist
Route::get('user-mgmt-hist/list', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'list']);
Route::post('user-mgmt-hist/store', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'store']);
Route::put('user-mgmt-hist/update/{id}', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'update']);
Route::delete('user-mgmt-hist/delete/{id}', [\App\Http\Controllers\History\Management\UserMgmtHistController::class, 'delete']);

// Routes for token_mst
Route::get('token-mst/list', [\App\Http\Controllers\Master\TokenMstController::class, 'list']);
Route::post('token-mst/store', [\App\Http\Controllers\Master\TokenMstController::class, 'store']);
Route::put('token-mst/update/{id}', [\App\Http\Controllers\Master\TokenMstController::class, 'update']);
Route::delete('token-mst/delete/{id}', [\App\Http\Controllers\Master\TokenMstController::class, 'delete']);

