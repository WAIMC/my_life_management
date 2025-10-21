/**
 * API Client - Axios Configuration and Request Handlers
 */

import axios, { AxiosInstance, AxiosRequestConfig, AxiosResponse, AxiosError } from 'axios';
import {
  API_CONFIG,
  HTTP_STATUS,
  REQUEST_HEADERS,
  CONTENT_TYPES,
  AUTH_TYPES,
} from '../constants';
import { getAccessToken, setAccessToken, clearTokens, isTokenExpired } from '../utils/token';
import { encodeQueryString } from '../utils/encode';
import type { ApiResponse, ApiRequestConfig, HttpMethod } from '../types/api.types';

/**
 * Create Axios Instance
 */
const createApiClient = (): AxiosInstance => {
  const instance = axios.create({
    baseURL: API_CONFIG.BASE_URL,
    timeout: API_CONFIG.TIMEOUT,
    headers: {
      [REQUEST_HEADERS.CONTENT_TYPE]: CONTENT_TYPES.JSON,
      [REQUEST_HEADERS.ACCEPT]: CONTENT_TYPES.JSON,
    },
  });

  // Request Interceptor
  instance.interceptors.request.use(
    (config) => {
      // Add auth token if required
      const token = getAccessToken();
      if (token && config.headers) {
        config.headers[REQUEST_HEADERS.AUTHORIZATION] = `${AUTH_TYPES.BEARER} ${token}`;
      }

      // Log request in development
      if (process.env.NODE_ENV === 'development') {
        console.log('🚀 API Request:', {
          method: config.method?.toUpperCase(),
          url: config.url,
          data: config.data,
          params: config.params,
        });
      }

      return config;
    },
    (error) => {
      console.error('❌ Request Error:', error);
      return Promise.reject(error);
    }
  );

  // Response Interceptor
  instance.interceptors.response.use(
    (response) => {
      // Log response in development
      if (process.env.NODE_ENV === 'development') {
        console.log('✅ API Response:', {
          url: response.config.url,
          status: response.status,
          data: response.data,
        });
      }

      return response;
    },
    async (error: AxiosError) => {
      const originalRequest: any = error.config;

      // Log error in development
      if (process.env.NODE_ENV === 'development') {
        console.error('❌ API Error:', {
          url: error.config?.url,
          status: error.response?.status,
          message: error.message,
          data: error.response?.data,
        });
      }

      // Handle 401 Unauthorized - Token expired
      if (error.response?.status === HTTP_STATUS.UNAUTHORIZED && !originalRequest._retry) {
        originalRequest._retry = true;

        try {
          // Try to refresh token
          const newToken = await refreshAccessToken();
          if (newToken) {
            setAccessToken(newToken);
            originalRequest.headers[REQUEST_HEADERS.AUTHORIZATION] = `${AUTH_TYPES.BEARER} ${newToken}`;
            return instance(originalRequest);
          }
        } catch (refreshError) {
          // Refresh failed, logout user
          clearTokens();
          if (typeof window !== 'undefined') {
            window.location.href = '/login';
          }
          return Promise.reject(refreshError);
        }
      }

      return Promise.reject(error);
    }
  );

  return instance;
};

// Create API client instance
const apiClient = createApiClient();

/**
 * Refresh Access Token
 */
const refreshAccessToken = async (): Promise<string | null> => {
  try {
    // Call refresh token API
    const response = await axios.post(
      `${API_CONFIG.BASE_URL}/auth/refresh`,
      {},
      {
        headers: {
          [REQUEST_HEADERS.AUTHORIZATION]: `${AUTH_TYPES.BEARER} ${getAccessToken()}`,
        },
      }
    );

    const { data } = response.data as ApiResponse;
    return data.access_token || null;
  } catch (error) {
    console.error('Failed to refresh token:', error);
    return null;
  }
};

/**
 * Generic API Request Handler
 */
export const apiRequest = async <T = any>(
  url: string,
  config: ApiRequestConfig = {}
): Promise<ApiResponse<T>> => {
  const {
    method = 'GET',
    headers = {},
    params = {},
    data = null,
    requireAuth = true,
    timeout = API_CONFIG.TIMEOUT,
  } = config;

  try {
    // Check token expiration if auth required
    if (requireAuth) {
      const token = getAccessToken();
      if (token && isTokenExpired(token)) {
        const newToken = await refreshAccessToken();
        if (newToken) {
          setAccessToken(newToken);
        } else {
          throw new Error('Token expired and refresh failed');
        }
      }
    }

    const requestConfig: AxiosRequestConfig = {
      url,
      method,
      headers,
      params,
      data,
      timeout,
    };

    const response: AxiosResponse<ApiResponse<T>> = await apiClient(requestConfig);
    return response.data;
  } catch (error) {
    throw handleApiError(error);
  }
};

/**
 * GET Request
 */
export const apiGet = <T = any>(
  url: string,
  params?: Record<string, any>,
  config?: Omit<ApiRequestConfig, 'method' | 'params'>
): Promise<ApiResponse<T>> => {
  return apiRequest<T>(url, { ...config, method: 'GET', params });
};

/**
 * POST Request
 */
export const apiPost = <T = any>(
  url: string,
  data?: any,
  config?: Omit<ApiRequestConfig, 'method' | 'data'>
): Promise<ApiResponse<T>> => {
  return apiRequest<T>(url, { ...config, method: 'POST', data });
};

/**
 * PUT Request
 */
export const apiPut = <T = any>(
  url: string,
  data?: any,
  config?: Omit<ApiRequestConfig, 'method' | 'data'>
): Promise<ApiResponse<T>> => {
  return apiRequest<T>(url, { ...config, method: 'PUT', data });
};

/**
 * PATCH Request
 */
export const apiPatch = <T = any>(
  url: string,
  data?: any,
  config?: Omit<ApiRequestConfig, 'method' | 'data'>
): Promise<ApiResponse<T>> => {
  return apiRequest<T>(url, { ...config, method: 'PATCH', data });
};

/**
 * DELETE Request
 */
export const apiDelete = <T = any>(
  url: string,
  config?: Omit<ApiRequestConfig, 'method'>
): Promise<ApiResponse<T>> => {
  return apiRequest<T>(url, { ...config, method: 'DELETE' });
};

/**
 * Handle API Errors
 */
export const handleApiError = (error: any): Error => {
  if (axios.isAxiosError(error)) {
    const axiosError = error as AxiosError<ApiResponse>;

    // Network error
    if (!axiosError.response) {
      return new Error('Network error. Please check your connection.');
    }

    // API error response
    const apiResponse = axiosError.response.data;
    if (apiResponse?.error) {
      const { code, messages } = apiResponse.error;
      
      if (Array.isArray(messages)) {
        return new Error(messages.join(', '));
      }
      
      if (typeof messages === 'string') {
        return new Error(messages);
      }

      // Fallback to status code message
      switch (code) {
        case HTTP_STATUS.BAD_REQUEST:
          return new Error('Bad request. Please check your input.');
        case HTTP_STATUS.UNAUTHORIZED:
          return new Error('Unauthorized. Please login again.');
        case HTTP_STATUS.FORBIDDEN:
          return new Error('You do not have permission to perform this action.');
        case HTTP_STATUS.NOT_FOUND:
          return new Error('Resource not found.');
        case HTTP_STATUS.UNPROCESSABLE_ENTITY:
          return new Error('Validation error. Please check your input.');
        case HTTP_STATUS.INTERNAL_SERVER_ERROR:
          return new Error('Server error. Please try again later.');
        default:
          return new Error('An error occurred. Please try again.');
      }
    }

    return new Error(axiosError.message);
  }

  return error instanceof Error ? error : new Error('Unknown error occurred');
};

/**
 * Check if response is successful
 */
export const isApiSuccess = <T>(response: ApiResponse<T>): boolean => {
  return !response.error.status && response.error.code === HTTP_STATUS.OK;
};

/**
 * Get error messages from API response
 */
export const getErrorMessages = (response: ApiResponse): string[] => {
  const { messages } = response.error;
  
  if (Array.isArray(messages)) {
    return messages;
  }
  
  if (typeof messages === 'string') {
    return [messages];
  }
  
  return ['An error occurred'];
};

export default apiClient;
