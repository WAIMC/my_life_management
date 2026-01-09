/**
 * Application Constants
 * Centralized constants to avoid hardcoded strings
 */

// Support email
export const SUPPORT_EMAIL = 'support@example.com';

// Sort order
export const SORT_ORDER = {
  ASC: 'asc',
  DESC: 'desc',
} as const;

export type SortOrder = typeof SORT_ORDER[keyof typeof SORT_ORDER];

// Common sort fields
export const SORT_FIELDS = {
  ID: 'id',
  NAME: 'name',
  CREATED_AT: 'created_at',
  UPDATED_AT: 'updated_at',
  ORDER: 'order',
  STATUS: 'status',
} as const;

export type SortField = typeof SORT_FIELDS[keyof typeof SORT_FIELDS];

// HTTP Methods
export const HTTP_METHODS = {
  GET: 'GET',
  POST: 'POST',
  PUT: 'PUT',
  PATCH: 'PATCH',
  DELETE: 'DELETE',
} as const;

export type HttpMethod = typeof HTTP_METHODS[keyof typeof HTTP_METHODS];

// Pagination
export const PAGINATION = {
  DEFAULT_PAGE: 1,
  DEFAULT_PER_PAGE: 20,
  DEFAULT_TOTAL_PAGES: 1,
  PER_PAGE_OPTIONS: [10, 20, 50, 100] as const,
} as const;

// Admin Routes
export const ADMIN_ROUTES = {
  DASHBOARD: '/admin',
  APIS: '/admin/apis',
  CATEGORIES: '/admin/categories',
  DEPARTMENTS: '/admin/departments',
  FEATURES: '/admin/features',
  SKILLS: '/admin/skills',
  SOCIALS: '/admin/socials',
  TOKENS: '/admin/tokens',
  BANNERS: '/admin/banners',
  SLIDERS: '/admin/sliders',
  POLICY_DEPARTMENTS: '/admin/policy-departments',
  SETTING_LINKS: '/admin/setting-links',
  SKILL_DESCRIPTIONS: '/admin/skill-descriptions',
  FILE_MANAGER: '/admin/file-manager',
  ADMINS: '/admin/admins',
  USERS: '/admin/users',
  ROLES: '/admin/roles',
} as const;
