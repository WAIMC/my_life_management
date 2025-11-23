/**
 * API Endpoints Constants
 */

// Base URL
export const API_BASE_URL =
  process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

// Authentication
export const AUTH_ENDPOINTS = {
  LOGIN: '/admin/account/login',
  LOGOUT: '/admin/account/logout',
  REFRESH: '/admin/account/refresh-token',
  ME: '/admin/account/me',
} as const;

// Master Data
export const MASTER_ENDPOINTS = {
  ADMIN: '/admin-mst',
  ROLE: '/role-mst',
  DEPARTMENT: '/department-mst',
  FEATURE: '/feature-mst',
  API: '/api-mst',
  LANGUAGE: '/language-mst',
  TRANSLATION: '/translation-mst',
  TOKEN: '/token-mst',
  POLICY_DEPARTMENT: '/policy-department-mst',
  ORIGINAL_TRANSLATOR: '/original-translator-mst',
} as const;

// Management Data
export const MANAGEMENT_ENDPOINTS = {
  BANNER: '/banner-mgmt',
  CATEGORY: '/category-mgmt',
  SKILL: '/skill-mgmt',
  SKILL_DESCRIPTION: '/skill-description-mgmt',
  SLIDER: '/slider-mgmt',
  SOCIAL: '/social-mgmt',
  USER: '/user-mgmt',
  SETTING_LINK: '/setting-link-mgmt',
} as const;

// Junction Tables
export const JUNCTION_ENDPOINTS = {
  ADMIN_ROLE: '/admin-role-mst',
  ADMIN_DEPARTMENT: '/admin-department-mst',
  API_ROLE: '/api-role-mst',
  DEPARTMENT_MANAGEMENT: '/department-management-mst',
  TRANSLATION_LANGUAGE: '/translation-language-mst',
  CATEGORY_SKILL: '/category-skill-mgmt',
} as const;

// File Upload
export const UPLOAD_ENDPOINT = '/upload';

// All endpoints combined
export const ENDPOINTS = {
  AUTH: AUTH_ENDPOINTS,
  MASTER: MASTER_ENDPOINTS,
  MANAGEMENT: MANAGEMENT_ENDPOINTS,
  JUNCTION: JUNCTION_ENDPOINTS,
  UPLOAD: UPLOAD_ENDPOINT,
} as const;
