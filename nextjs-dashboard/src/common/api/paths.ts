/**
 * API Path Helpers
 */

import { API_ENDPOINTS } from '../constants';
import { encodeQueryString } from '../utils/encode';

/**
 * Build full API URL
 */
export const buildApiUrl = (path: string, params?: Record<string, any>): string => {
  let url = path.startsWith('/') ? path : `/${path}`;
  
  if (params && Object.keys(params).length > 0) {
    const queryString = encodeQueryString(params);
    url += `?${queryString}`;
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
    list: (params?: Record<string, any>) => buildApiUrl(API_ENDPOINTS.USERS.LIST, params),
    detail: (id: number | string) => API_ENDPOINTS.USERS.DETAIL(id),
    create: () => API_ENDPOINTS.USERS.CREATE,
    update: (id: number | string) => API_ENDPOINTS.USERS.UPDATE(id),
    delete: (id: number | string) => API_ENDPOINTS.USERS.DELETE(id),
    profile: () => API_ENDPOINTS.USERS.PROFILE,
  },

  // Accounts
  accounts: {
    list: (params?: Record<string, any>) => buildApiUrl(API_ENDPOINTS.ACCOUNTS.LIST, params),
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
  filter: (filters: Record<string, any>) => filters,
  sort: (field: string, order: 'asc' | 'desc' = 'asc') => ({ sort: field, order }),
};
