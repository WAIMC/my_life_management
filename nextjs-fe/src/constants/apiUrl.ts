/**
 * API Endpoints Constants
 * Auto-generated from Laravel API routes
// Auth Endpoints
export const LOGIN = '/admin/credential/login';
export const LOGOUT = '/admin/credential/trust/logout';
export const REFRESH_TOKEN = '/admin/credential/trust/refresh-token';
export const ME = '/admin/credential/me';

// Admin Master
export const ADMIN_MST_LIST = '/admin/admin-mst/list';
export const ADMIN_MST_STORE = '/admin/admin-mst/store';
export const ADMIN_MST_UPDATE = '/admin/admin-mst/update'; // + /{id}
export const ADMIN_MST_DELETE = '/admin/admin-mst/delete'; // + /{id}

// Admin Department Master
export const ADMIN_DEPARTMENT_MST_LIST = '/admin/admin-department-mst/list';
export const ADMIN_DEPARTMENT_MST_UPDATE = '/admin/admin-department-mst/update';

// Admin Role Master
export const ADMIN_ROLE_MST_LIST = '/admin/admin-role-mst/list';
export const ADMIN_ROLE_MST_UPDATE = '/admin/admin-role-mst/update';

// API Master
export const API_MST_LIST = '/admin/api-mst/list';
export const API_MST_STORE = '/admin/api-mst/store';
export const API_MST_UPDATE = '/admin/api-mst/update'; // + /{id}
export const API_MST_DELETE = '/admin/api-mst/delete'; // + /{id}

// API Role Master
export const API_ROLE_MST_LIST = '/admin/api-role-mst/list';
export const API_ROLE_MST_UPDATE = '/admin/api-role-mst/update';

// Department Master
export const DEPARTMENT_MST_LIST = '/admin/department-mst/list';
export const DEPARTMENT_MST_STORE = '/admin/department-mst/store';
export const DEPARTMENT_MST_UPDATE = '/admin/department-mst/update'; // + /{id}
export const DEPARTMENT_MST_DELETE = '/admin/department-mst/delete'; // + /{id}

// Department Management Master
export const DEPARTMENT_MANAGEMENT_MST_LIST = '/admin/department-management-mst/list';
export const DEPARTMENT_MANAGEMENT_MST_UPDATE = '/admin/department-management-mst/update';

// Feature Master
export const FEATURE_MST_LIST = '/admin/feature-mst/list';
export const FEATURE_MST_STORE = '/admin/feature-mst/store';
export const FEATURE_MST_UPDATE = '/admin/feature-mst/update'; // + /{id}
export const FEATURE_MST_DELETE = '/admin/feature-mst/delete'; // + /{id}

// Language Master
export const LANGUAGE_MST_LIST = '/admin/language-mst/list';
export const LANGUAGE_MST_STORE = '/admin/language-mst/store';
export const LANGUAGE_MST_UPDATE = '/admin/language-mst/update'; // + /{id}
export const LANGUAGE_MST_DELETE = '/admin/language-mst/delete'; // + /{id}

// Original Translator Master
export const ORIGINAL_TRANSLATOR_MST_LIST = '/admin/original-translator-mst/list';
export const ORIGINAL_TRANSLATOR_MST_STORE = '/admin/original-translator-mst/store';
export const ORIGINAL_TRANSLATOR_MST_UPDATE = '/admin/original-translator-mst/update'; // + /{id}
export const ORIGINAL_TRANSLATOR_MST_DELETE = '/admin/original-translator-mst/delete'; // + /{id}

// Policy Department Master
export const POLICY_DEPARTMENT_MST_LIST = '/admin/policy-department-mst/list';
export const POLICY_DEPARTMENT_MST_STORE = '/admin/policy-department-mst/store';
export const POLICY_DEPARTMENT_MST_UPDATE = '/admin/policy-department-mst/update'; // + /{id}
export const POLICY_DEPARTMENT_MST_DELETE = '/admin/policy-department-mst/delete'; // + /{id}

// Role Master
export const ROLE_MST_LIST = '/admin/role-mst/list';
export const ROLE_MST_STORE = '/admin/role-mst/store';
export const ROLE_MST_UPDATE = '/admin/role-mst/update'; // + /{id}
export const ROLE_MST_DELETE = '/admin/role-mst/delete'; // + /{id}

// Token Master
export const TOKEN_MST_LIST = '/admin/token-mst/list';
export const TOKEN_MST_STORE = '/admin/token-mst/store';
export const TOKEN_MST_UPDATE = '/admin/token-mst/update'; // + /{id}
export const TOKEN_MST_DELETE = '/admin/token-mst/delete'; // + /{id}

// Translation Master
export const TRANSLATION_MST_LIST = '/admin/translation-mst/list';
export const TRANSLATION_MST_STORE = '/admin/translation-mst/store';
export const TRANSLATION_MST_UPDATE = '/admin/translation-mst/update'; // + /{id}
export const TRANSLATION_MST_DELETE = '/admin/translation-mst/delete'; // + /{id}

// Translation Language Master
export const TRANSLATION_LANGUAGE_MST_LIST = '/admin/translation-language-mst/list';
export const TRANSLATION_LANGUAGE_MST_UPDATE = '/admin/translation-language-mst/update';

// ============================================
// MANAGEMENT DATA ENDPOINTS
// ============================================

// Banner Management
export const BANNER_MGMT_LIST = '/admin/banner-mgmt/list';
export const BANNER_MGMT_STORE = '/admin/banner-mgmt/store';
export const BANNER_MGMT_UPDATE = '/admin/banner-mgmt/update'; // + /{id}
export const BANNER_MGMT_DELETE = '/admin/banner-mgmt/delete'; // + /{id}

// Category Management
export const CATEGORY_MGMT_LIST = '/admin/category-mgmt/list';
export const CATEGORY_MGMT_STORE = '/admin/category-mgmt/store';
export const CATEGORY_MGMT_UPDATE = '/admin/category-mgmt/update'; // + /{id}
export const CATEGORY_MGMT_DELETE = '/admin/category-mgmt/delete'; // + /{id}

// Category Skill Management
export const CATEGORY_SKILL_MGMT_LIST = '/admin/category-skill-mgmt/list';
export const CATEGORY_SKILL_MGMT_UPDATE = '/admin/category-skill-mgmt/update';

// Setting Link Management
export const SETTING_LINK_MGMT_LIST = '/admin/setting-link-mgmt/list';
export const SETTING_LINK_MGMT_STORE = '/admin/setting-link-mgmt/store';
export const SETTING_LINK_MGMT_UPDATE = '/admin/setting-link-mgmt/update'; // + /{id}
export const SETTING_LINK_MGMT_DELETE = '/admin/setting-link-mgmt/delete'; // + /{id}

// Skill Management
export const SKILL_MGMT_LIST = '/admin/skill-mgmt/list';
export const SKILL_MGMT_STORE = '/admin/skill-mgmt/store';
export const SKILL_MGMT_UPDATE = '/admin/skill-mgmt/update'; // + /{id}
export const SKILL_MGMT_DELETE = '/admin/skill-mgmt/delete'; // + /{id}

// Skill Description Management
export const SKILL_DESCRIPTION_MGMT_LIST = '/admin/skill-description-mgmt/list';
export const SKILL_DESCRIPTION_MGMT_STORE = '/admin/skill-description-mgmt/store';
export const SKILL_DESCRIPTION_MGMT_UPDATE = '/admin/skill-description-mgmt/update'; // + /{id}
export const SKILL_DESCRIPTION_MGMT_DELETE = '/admin/skill-description-mgmt/delete'; // + /{id}

// Slider Management
export const SLIDER_MGMT_LIST = '/admin/slider-mgmt/list';
export const SLIDER_MGMT_STORE = '/admin/slider-mgmt/store';
export const SLIDER_MGMT_UPDATE = '/admin/slider-mgmt/update'; // + /{id}
export const SLIDER_MGMT_DELETE = '/admin/slider-mgmt/delete'; // + /{id}

// Social Management
export const SOCIAL_MGMT_LIST = '/admin/social-mgmt/list';
export const SOCIAL_MGMT_STORE = '/admin/social-mgmt/store';
export const SOCIAL_MGMT_UPDATE = '/admin/social-mgmt/update'; // + /{id}
export const SOCIAL_MGMT_DELETE = '/admin/social-mgmt/delete'; // + /{id}

// User Management
export const USER_MGMT_LIST = '/admin/user-mgmt/list';
export const USER_MGMT_STORE = '/admin/user-mgmt/store';
export const USER_MGMT_UPDATE = '/admin/user-mgmt/update'; // + /{id}
export const USER_MGMT_DELETE = '/admin/user-mgmt/delete'; // + /{id}

// ============================================
// HISTORY ENDPOINTS (Master)
// ============================================

// Admin Master History
export const ADMIN_MST_HIST_LIST = '/admin/admin-mst-hist/list';
export const ADMIN_MST_HIST_STORE = '/admin/admin-mst-hist/store';
export const ADMIN_MST_HIST_UPDATE = '/admin/admin-mst-hist/update'; // + /{id}
export const ADMIN_MST_HIST_DELETE = '/admin/admin-mst-hist/delete'; // + /{id}

// API Master History
export const API_MST_HIST_LIST = '/admin/api-mst-hist/list';
export const API_MST_HIST_STORE = '/admin/api-mst-hist/store';
export const API_MST_HIST_UPDATE = '/admin/api-mst-hist/update'; // + /{id}
export const API_MST_HIST_DELETE = '/admin/api-mst-hist/delete'; // + /{id}

// Department Master History
export const DEPARTMENT_MST_HIST_LIST = '/admin/department-mst-hist/list';
export const DEPARTMENT_MST_HIST_STORE = '/admin/department-mst-hist/store';
export const DEPARTMENT_MST_HIST_UPDATE = '/admin/department-mst-hist/update'; // + /{id}
export const DEPARTMENT_MST_HIST_DELETE = '/admin/department-mst-hist/delete'; // + /{id}

// Feature Master History
export const FEATURE_MST_HIST_LIST = '/admin/feature-mst-hist/list';
export const FEATURE_MST_HIST_STORE = '/admin/feature-mst-hist/store';
export const FEATURE_MST_HIST_UPDATE = '/admin/feature-mst-hist/update'; // + /{id}
export const FEATURE_MST_HIST_DELETE = '/admin/feature-mst-hist/delete'; // + /{id}

// Language Master History
export const LANGUAGE_MST_HIST_LIST = '/admin/language-mst-hist/list';
export const LANGUAGE_MST_HIST_STORE = '/admin/language-mst-hist/store';
export const LANGUAGE_MST_HIST_UPDATE = '/admin/language-mst-hist/update'; // + /{id}
export const LANGUAGE_MST_HIST_DELETE = '/admin/language-mst-hist/delete'; // + /{id}

// Original Translator Master History
export const ORIGINAL_TRANSLATOR_MST_HIST_LIST = '/admin/original-translator-mst-hist/list';
export const ORIGINAL_TRANSLATOR_MST_HIST_STORE = '/admin/original-translator-mst-hist/store';
export const ORIGINAL_TRANSLATOR_MST_HIST_UPDATE = '/admin/original-translator-mst-hist/update'; // + /{id}
export const ORIGINAL_TRANSLATOR_MST_HIST_DELETE = '/admin/original-translator-mst-hist/delete'; // + /{id}

// Policy Department Master History
export const POLICY_DEPARTMENT_MST_HIST_LIST = '/admin/policy-department-mst-hist/list';
export const POLICY_DEPARTMENT_MST_HIST_STORE = '/admin/policy-department-mst-hist/store';
export const POLICY_DEPARTMENT_MST_HIST_UPDATE = '/admin/policy-department-mst-hist/update'; // + /{id}
export const POLICY_DEPARTMENT_MST_HIST_DELETE = '/admin/policy-department-mst-hist/delete'; // + /{id}

// Role Master History
export const ROLE_MST_HIST_LIST = '/admin/role-mst-hist/list';
export const ROLE_MST_HIST_STORE = '/admin/role-mst-hist/store';
export const ROLE_MST_HIST_UPDATE = '/admin/role-mst-hist/update'; // + /{id}
export const ROLE_MST_HIST_DELETE = '/admin/role-mst-hist/delete'; // + /{id}

// Translation Master History
export const TRANSLATION_MST_HIST_LIST = '/admin/translation-mst-hist/list';
export const TRANSLATION_MST_HIST_STORE = '/admin/translation-mst-hist/store';
export const TRANSLATION_MST_HIST_UPDATE = '/admin/translation-mst-hist/update'; // + /{id}
export const TRANSLATION_MST_HIST_DELETE = '/admin/translation-mst-hist/delete'; // + /{id}

// ============================================
// HISTORY ENDPOINTS (Management)
// ============================================

// Banner Management History
export const BANNER_MGMT_HIST_LIST = '/admin/banner-mgmt-hist/list';
export const BANNER_MGMT_HIST_STORE = '/admin/banner-mgmt-hist/store';
export const BANNER_MGMT_HIST_UPDATE = '/admin/banner-mgmt-hist/update'; // + /{id}
export const BANNER_MGMT_HIST_DELETE = '/admin/banner-mgmt-hist/delete'; // + /{id}

// Category Management History
export const CATEGORY_MGMT_HIST_LIST = '/admin/category-mgmt-hist/list';
export const CATEGORY_MGMT_HIST_STORE = '/admin/category-mgmt-hist/store';
export const CATEGORY_MGMT_HIST_UPDATE = '/admin/category-mgmt-hist/update'; // + /{id}
export const CATEGORY_MGMT_HIST_DELETE = '/admin/category-mgmt-hist/delete'; // + /{id}

// Setting Link Management History
export const SETTING_LINK_MGMT_HIST_LIST = '/admin/setting-link-mgmt-hist/list';
export const SETTING_LINK_MGMT_HIST_STORE = '/admin/setting-link-mgmt-hist/store';
export const SETTING_LINK_MGMT_HIST_UPDATE = '/admin/setting-link-mgmt-hist/update'; // + /{id}
export const SETTING_LINK_MGMT_HIST_DELETE = '/admin/setting-link-mgmt-hist/delete'; // + /{id}

// Skill Management History
export const SKILL_MGMT_HIST_LIST = '/admin/skill-mgmt-hist/list';
export const SKILL_MGMT_HIST_STORE = '/admin/skill-mgmt-hist/store';
export const SKILL_MGMT_HIST_UPDATE = '/admin/skill-mgmt-hist/update'; // + /{id}
export const SKILL_MGMT_HIST_DELETE = '/admin/skill-mgmt-hist/delete'; // + /{id}

// Skill Description Management History
export const SKILL_DESCRIPTION_MGMT_HIST_LIST = '/admin/skill-description-mgmt-hist/list';
export const SKILL_DESCRIPTION_MGMT_HIST_STORE = '/admin/skill-description-mgmt-hist/store';
export const SKILL_DESCRIPTION_MGMT_HIST_UPDATE = '/admin/skill-description-mgmt-hist/update'; // + /{id}
export const SKILL_DESCRIPTION_MGMT_HIST_DELETE = '/admin/skill-description-mgmt-hist/delete'; // + /{id}

// Slider Management History
export const SLIDER_MGMT_HIST_LIST = '/admin/slider-mgmt-hist/list';
export const SLIDER_MGMT_HIST_STORE = '/admin/slider-mgmt-hist/store';
export const SLIDER_MGMT_HIST_UPDATE = '/admin/slider-mgmt-hist/update'; // + /{id}
export const SLIDER_MGMT_HIST_DELETE = '/admin/slider-mgmt-hist/delete'; // + /{id}

// Social Management History
export const SOCIAL_MGMT_HIST_LIST = '/admin/social-mgmt-hist/list';
export const SOCIAL_MGMT_HIST_STORE = '/admin/social-mgmt-hist/store';
export const SOCIAL_MGMT_HIST_UPDATE = '/admin/social-mgmt-hist/update'; // + /{id}
export const SOCIAL_MGMT_HIST_DELETE = '/admin/social-mgmt-hist/delete'; // + /{id}

// User Management History
export const USER_MGMT_HIST_LIST = '/admin/user-mgmt-hist/list';
export const USER_MGMT_HIST_STORE = '/admin/user-mgmt-hist/store';
export const USER_MGMT_HIST_UPDATE = '/admin/user-mgmt-hist/update'; // + /{id}
export const USER_MGMT_HIST_DELETE = '/admin/user-mgmt-hist/delete'; // + /{id}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Build URL with ID parameter
 * @param baseUrl - Base URL endpoint
 * @param id - ID to append
 * @returns Complete URL with ID
 */
export const withId = (baseUrl: string, id: string | number): string => {
  return `${baseUrl}/${id}`;
};

/**
 * Build URL with query parameters
 * @param baseUrl - Base URL endpoint
 * @param params - Query parameters object
 * @returns Complete URL with query string
 */
export const withParams = (baseUrl: string, params: Record<string, any>): string => {
  const queryString = new URLSearchParams(
    Object.entries(params)
      .filter(([_, value]) => value !== undefined && value !== null)
      .map(([key, value]) => [key, String(value)])
  ).toString();
  
  return queryString ? `${baseUrl}?${queryString}` : baseUrl;
};