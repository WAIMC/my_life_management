/**
 * API Endpoints Constants
 */

// Base URL
export const API_BASE_URL =
  process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

// Authentication
export const AUTH_ENDPOINTS = {
  LOGIN: '/admin/credential/login',
  LOGOUT: '/admin/credential/trust/logout',
  REFRESH: '/admin/credential/trust/refresh-token',
  ME: '/admin/credential/me',
} as const;

// Master Data
export const MASTER_ENDPOINTS = {
  ADMIN: '/admin/admin-mst',
  ROLE: '/admin/role-mst',
  DEPARTMENT: '/admin/department-mst',
  FEATURE: '/admin/feature-mst',
  API: '/admin/api-mst',
  TOKEN: '/admin/token-mst',
  POLICY_DEPARTMENT: '/admin/policy-department-mst',
} as const;

// Management Data
export const MANAGEMENT_ENDPOINTS = {
  BANNER: '/admin/banner-mgmt',
  CATEGORY: '/admin/category-mgmt',
  SKILL: '/admin/skill-mgmt',
  SKILL_DESCRIPTION: '/admin/skill-description-mgmt',
  SLIDER: '/admin/slider-mgmt',
  SOCIAL: '/admin/social-mgmt',
  USER: '/admin/user-mgmt',
  SETTING_LINK: '/admin/setting-link-mgmt',
} as const;

// Junction Tables
export const JUNCTION_ENDPOINTS = {
  ADMIN_ROLE: '/admin/admin-role-mst',
  ADMIN_DEPARTMENT: '/admin/admin-department-mst',
  API_ROLE: '/admin/api-role-mst',
  DEPARTMENT_MANAGEMENT: '/admin/department-management-mst',
  CATEGORY_SKILL: '/admin/category-skill-mgmt',
} as const;

// File Upload
export const UPLOAD_ENDPOINT = '/upload';



// History Endpoints
export const HISTORY_ENDPOINTS = {
  // Master History
  ADMIN: '/admin/admin-mst-hist',
  API: '/admin/api-mst-hist',
  DEPARTMENT: '/admin/department-mst-hist',
  FEATURE: '/admin/feature-mst-hist',
  POLICY_DEPARTMENT: '/admin/policy-department-mst-hist',
  ROLE: '/admin/role-mst-hist',

  // Management History
  BANNER: '/admin/banner-mgmt-hist',
  CATEGORY: '/admin/category-mgmt-hist',
  SETTING_LINK: '/admin/setting-link-mgmt-hist',
  SKILL: '/admin/skill-mgmt-hist',
  SKILL_DESCRIPTION: '/admin/skill-description-mgmt-hist',
  SLIDER: '/admin/slider-mgmt-hist',
  SOCIAL: '/admin/social-mgmt-hist',
  USER: '/admin/user-mgmt-hist',
} as const;

// All endpoints combined
export const ENDPOINTS = {
  AUTH: AUTH_ENDPOINTS,
  MASTER: MASTER_ENDPOINTS,
  MANAGEMENT: MANAGEMENT_ENDPOINTS,
  HISTORY: HISTORY_ENDPOINTS,
  JUNCTION: JUNCTION_ENDPOINTS,
  UPLOAD: UPLOAD_ENDPOINT,

  // Media Management
  MEDIA: '/admin/media-mgmt',
} as const;
