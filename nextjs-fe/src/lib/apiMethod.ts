import axiosInstance from './apiInstance';
import { AxiosRequestConfig, isAxiosError } from 'axios';

// Interface cho request config
interface RequestConfig extends AxiosRequestConfig {
  params?: Record<string, unknown>;
  retries?: number; // Số lần retry khi network error
}

// Helper: retry logic cho network errors
const executeWithRetry = async <T>(
  fn: () => Promise<T>,
  retries: number = 3,
  delay: number = 1000
): Promise<T> => {
  try {
    return await fn();
  } catch (error) {
    // Chỉ retry network errors, không retry HTTP errors
    const isNetworkError = !isAxiosError(error) || !error.response;
    
    if (isNetworkError && retries > 0) {
      // Exponential backoff
      await new Promise(resolve => setTimeout(resolve, delay));
      return executeWithRetry(fn, retries - 1, delay * 2);
    }
    
    throw error;
  }
};

/**
 * GET request
 * @param url - API endpoint
 * @param params - Query parameters
 * @param config - Additional axios config
 */
export const apiGet = async <T = unknown>(
  url: string,
  params?: Record<string, unknown>,
  config?: RequestConfig
): Promise<T> => {
  const { retries = 2, ...restConfig } = config || {};
  
  return executeWithRetry(async () => {
    const response = await axiosInstance.get<T>(url, {
      ...restConfig,
      params: params || config?.params,
    });
    return response.data;
  }, retries);
};

/**
 * POST request
 * @param url - API endpoint
 * @param data - Request body (JSON payload)
 * @param params - Query parameters (optional)
 * @param config - Additional axios config
 */
export const apiPost = async <T = unknown>(
  url: string,
  data?: unknown,
  params?: Record<string, unknown>,
  config?: RequestConfig
): Promise<T> => {
  const { retries = 1, ...restConfig } = config || {};
  
  return executeWithRetry(async () => {
    const response = await axiosInstance.post<T>(url, data, {
      ...restConfig,
      params: params || config?.params,
    });
    return response.data;
  }, retries);
};

/**
 * PUT request
 * @param url - API endpoint
 * @param data - Request body (JSON payload)
 * @param params - Query parameters (optional)
 * @param config - Additional axios config
 */
export const apiPut = async <T = unknown>(
  url: string,
  data?: unknown,
  params?: Record<string, unknown>,
  config?: RequestConfig
): Promise<T> => {
  const { retries = 1, ...restConfig } = config || {};
  
  return executeWithRetry(async () => {
    const response = await axiosInstance.put<T>(url, data, {
      ...restConfig,
      params: params || config?.params,
    });
    return response.data;
  }, retries);
};

/**
 * PATCH request
 * @param url - API endpoint
 * @param data - Request body (JSON payload)
 * @param params - Query parameters (optional)
 * @param config - Additional axios config
 */
export const apiPatch = async <T = unknown>(
  url: string,
  data?: unknown,
  params?: Record<string, unknown>,
  config?: RequestConfig
): Promise<T> => {
  const { retries = 1, ...restConfig } = config || {};
  
  return executeWithRetry(async () => {
    const response = await axiosInstance.patch<T>(url, data, {
      ...restConfig,
      params: params || config?.params,
    });
    return response.data;
  }, retries);
};

/**
 * DELETE request
 * @param url - API endpoint
 * @param params - Query parameters (optional)
 * @param config - Additional axios config
 */
export const apiDelete = async <T = unknown>(
  url: string,
  params?: Record<string, unknown>,
  config?: RequestConfig
): Promise<T> => {
  const { retries = 2, ...restConfig } = config || {};
  
  return executeWithRetry(async () => {
    const response = await axiosInstance.delete<T>(url, {
      ...restConfig,
      params: params || config?.params,
    });
    return response.data;
  }, retries);
};
