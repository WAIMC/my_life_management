/**
 * Common API Response Types
 */

export interface ApiErrorResponse {
  // `status` indicates whether an error occurred. true = error, false = OK
  status: boolean;
  code: number;
  messages: string | string[] | null;
}

export interface ApiResponse<T = unknown> {
  data: T;
  error: ApiErrorResponse;
}

export interface AuthTokenResponse {
  auth_type: string;
  ttl: number;
  access_token: string;
  refresh_token?: string;
}

export interface PaginationMeta {
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
}

export interface PaginatedResponse<T = unknown> {
  data: T[];
  meta: PaginationMeta;
}

export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

export interface ApiRequestConfig {
  method?: HttpMethod;
  headers?: Record<string, string>;
  params?: Record<string, unknown>;
  data?: unknown;
  requireAuth?: boolean;
  timeout?: number;
}
