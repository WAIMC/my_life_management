/**
 * Common API Response Types
 */

export interface ApiErrorResponse {
  status: boolean; // false = success, true = error
  code: number;
  messages: string | string[] | null;
}

export interface ApiResponse<T = any> {
  data: T;
  error: ApiErrorResponse;
}

export interface AuthTokenResponse {
  auth_type: string;
  ttl: number;
  access_token: string;
}

export interface PaginationMeta {
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
}

export interface PaginatedResponse<T = any> {
  data: T[];
  meta: PaginationMeta;
}

export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

export interface ApiRequestConfig {
  method?: HttpMethod;
  headers?: Record<string, string>;
  params?: Record<string, any>;
  data?: any;
  requireAuth?: boolean;
  timeout?: number;
}
