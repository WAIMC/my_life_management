import axios, {
  AxiosInstance,
  AxiosError,
  InternalAxiosRequestConfig,
} from "axios";
import { handleCommonError } from "./error-handler";
import { ApiResponse } from "@/shared/utils/types/api";
import { authLock } from "@/shared/utils/auth-lock";
import { API_ENDPOINTS, API_BASE_URL } from "@/shared/api/endpoints";

/**
 * Unified API Client
 *
 * Simplified to rely on HttpOnly cookies for authentication.
 */

class ApiClient {
  private client: AxiosInstance;

  constructor() {
    this.client = axios.create({
      baseURL: API_BASE_URL,
      timeout: 30000, // 30 seconds
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      withCredentials: true, // Always send cookies
    });

    this.setupInterceptors();
  }

  private setupInterceptors() {
    // REQUEST INTERCEPTOR
    this.client.interceptors.request.use(
      (config: InternalAxiosRequestConfig) => {
        // Add cache control headers per request
        config.headers["Cache-Control"] = "no-cache";
        config.headers["Pragma"] = "no-cache";

        return config;
      },
      (error: AxiosError) => {
        return Promise.reject(error);
      }
    );

    // RESPONSE INTERCEPTOR
    this.client.interceptors.response.use(
      (response) => {
        return response;
      },
      async (error: AxiosError) => {
        const originalRequest = error.config as InternalAxiosRequestConfig & {
          _retry?: boolean;
        };

        // Handle 401 Unauthorized
        if (
          error.response?.status === 401 &&
          originalRequest &&
          !originalRequest._retry
        ) {
          // Skip if it's already a refresh request or login request
          if (
            originalRequest.url?.includes(API_ENDPOINTS.AUTH.REFRESH) ||
            originalRequest.url?.includes(API_ENDPOINTS.AUTH.LOGIN)
          ) {
            return Promise.reject(error);
          }

          originalRequest._retry = true;

          try {
            // Check if another tab is refreshing
            if (authLock.isLocked()) {
              await this.waitForLock();
              return this.client(originalRequest);
            }

            // Try to acquire lock
            if (await authLock.acquire()) {
              try {
                // Call Refresh API directly using a fresh axios instance to avoid interceptors
                await axios.post(
                  `${this.client.defaults.baseURL}${API_ENDPOINTS.AUTH.REFRESH}`,
                  {},
                  { withCredentials: true }
                );

                // Refresh successful, retry original request
                return this.client(originalRequest);
              } catch (refreshError) {
                // Refresh failed -> Logout
                // We can't easily call authService.logout() here due to cycles/context
                // But the 401 from refresh will propagate to the caller (AuthProvider)
                // if we were calling it from there.
                // However, here we are inside an interceptor.
                // We should probably broadcast LOGOUT or let the UI handle it.
                // For now, reject the promise, and let the UI redirect.
                return Promise.reject(refreshError);
              } finally {
                authLock.release();
              }
            } else {
              // Failed to acquire lock (race condition), wait and retry
              await this.waitForLock();
              return this.client(originalRequest);
            }
          } catch (err) {
            return Promise.reject(err);
          }
        }

        return handleCommonError(error as AxiosError<ApiResponse<unknown>>);
      }
    );
  }

  private async waitForLock(): Promise<void> {
    const checkInterval = 100; // 100ms
    const maxWait = 5000; // 5s
    let waited = 0;

    while (authLock.isLocked() && waited < maxWait) {
      await new Promise((resolve) => setTimeout(resolve, checkInterval));
      waited += checkInterval;
    }
  }

  /**
   * Generic GET request
   */
  async get<T>(
    url: string,
    params?: Record<string, any>
  ): Promise<ApiResponse<T>> {
    const response = await this.client.get<ApiResponse<T>>(url, { params });
    return response.data;
  }

  /**
   * Generic POST request
   */
  async post<T>(
    url: string,
    data?: any,
    config?: any
  ): Promise<ApiResponse<T>> {
    const response = await this.client.post<ApiResponse<T>>(url, data, config);
    return response.data;
  }

  /**
   * Generic PUT request
   */
  async put<T>(url: string, data?: any): Promise<ApiResponse<T>> {
    const response = await this.client.put<ApiResponse<T>>(url, data);
    return response.data;
  }

  /**
   * Generic DELETE request
   */
  async delete<T>(url: string, data?: any): Promise<ApiResponse<T>> {
    const response = await this.client.delete<ApiResponse<T>>(url, { data });
    return response.data;
  }

  /**
   * Get raw axios instance for custom requests
   */
  getAxiosInstance(): AxiosInstance {
    return this.client;
  }
}

// Export singleton instance
export const apiClient = new ApiClient();

// Export class for testing
export { ApiClient };

// Export default for backward compatibility
export default apiClient.getAxiosInstance();
