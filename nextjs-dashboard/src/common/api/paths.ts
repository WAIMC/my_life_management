/**
 * API Path Helpers
 */

import { API_ENDPOINTS } from '../constants';
import { encodeQueryString } from '../utils/encode';

/**
 * Normalize a path segment (trim trailing/leading slashes)
 */
const trimSlashes = (s: string) => s.replace(/^\/+|\/+$/g, '');

/**
 * Build full API URL relative to the API base path. If `path` is already an absolute URL, it is returned as-is (with query params appended).
 */
export const buildApiUrl = (path: string, params?: Record<string, unknown>): string => {
  const hasScheme = /^https?:\/\//i.test(path);

  let url = path;
  if (!hasScheme) {
    url = `/${trimSlashes(path)}`;
  }

  if (params && Object.keys(params).length > 0) {
    const queryString = encodeQueryString(params);
    url += (url.includes('?') ? '&' : '?') + queryString;
  }

  return url;
};

/**
 * API Path Generators
 */
export const apiPaths = {
  // Auth
  auth: {
    login: () => API_ENDPOINTS.AUTH.LOGIN,
    logout: () => API_ENDPOINTS.AUTH.LOGOUT,
    refresh: () => API_ENDPOINTS.AUTH.REFRESH,
    register: () => API_ENDPOINTS.AUTH.REGISTER,
    verifyEmail: () => API_ENDPOINTS.AUTH.VERIFY_EMAIL,
    forgotPassword: () => API_ENDPOINTS.AUTH.FORGOT_PASSWORD,
    resetPassword: () => API_ENDPOINTS.AUTH.RESET_PASSWORD,
  },

  // Users
  users: {
  list: (params?: Record<string, unknown>) => buildApiUrl(API_ENDPOINTS.USERS.LIST, params),
    detail: (id: number | string) => API_ENDPOINTS.USERS.DETAIL(id),
    create: () => API_ENDPOINTS.USERS.CREATE,
    update: (id: number | string) => API_ENDPOINTS.USERS.UPDATE(id),
    delete: (id: number | string) => API_ENDPOINTS.USERS.DELETE(id),
    profile: () => API_ENDPOINTS.USERS.PROFILE,
  },

  // Accounts
  accounts: {
  list: (params?: Record<string, unknown>) => buildApiUrl(API_ENDPOINTS.ACCOUNTS.LIST, params),
    detail: (id: number | string) => API_ENDPOINTS.ACCOUNTS.DETAIL(id),
    create: () => API_ENDPOINTS.ACCOUNTS.CREATE,
    update: (id: number | string) => API_ENDPOINTS.ACCOUNTS.UPDATE(id),
    delete: (id: number | string) => API_ENDPOINTS.ACCOUNTS.DELETE(id),
  },
};

/**
 * Common query parameter builders
 */
export const queryParams = {
  pagination: (page: number = 1, limit: number = 10) => ({ page, limit }),
  search: (query: string) => ({ search: query }),
  filter: (filters: Record<string, unknown>) => filters,
  sort: (field: string, order: 'asc' | 'desc' = 'asc') => ({ sort: field, order }),
};
