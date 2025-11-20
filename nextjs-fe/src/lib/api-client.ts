import axios, { AxiosInstance, AxiosError, InternalAxiosRequestConfig } from 'axios';
import type { ApiResponse, ApiErrorResponse } from './types/api';

/**
 * Centralized API Client with JWT authentication and automatic token refresh
 */
class ApiClient {
  private client: AxiosInstance;
  private accessToken: string | null = null;
  private refreshPromise: Promise<string> | null = null;

  constructor() {
    this.client = axios.create({
      baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      withCredentials: true, // Important for refresh token cookie
    });

    this.setupInterceptors();
  }

  private setupInterceptors() {
    // Request interceptor - add access token to headers
    this.client.interceptors.request.use(
      (config) => {
        if (this.accessToken) {
          config.headers.Authorization = `Bearer ${this.accessToken}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    // Response interceptor - handle token refresh on 401
    this.client.interceptors.response.use(
      (response) => response,
      async (error: AxiosError<ApiErrorResponse>) => {
        const originalRequest = error.config as InternalAxiosRequestConfig & {
          _retry?: boolean;
        };

        // If 401 and not already retrying, try to refresh token
        if (error.response?.status === 401 && !originalRequest._retry) {
          originalRequest._retry = true;

          try {
            // Use promise to prevent multiple refresh requests
            if (!this.refreshPromise) {
              this.refreshPromise = this.refreshAccessToken();
            }

            const newToken = await this.refreshPromise;
            this.refreshPromise = null;

            // Retry original request with new token
            originalRequest.headers.Authorization = `Bearer ${newToken}`;
            return this.client(originalRequest);
          } catch (refreshError) {
            // Refresh failed, clear token and redirect to login
            this.clearAccessToken();
            if (typeof window !== 'undefined') {
              window.location.href = '/login';
            }
            return Promise.reject(refreshError);
          }
        }

        return Promise.reject(error);
      }
    );
  }

  /**
   * Refresh access token using refresh token cookie
   */
  private async refreshAccessToken(): Promise<string> {
    try {
      const response = await this.client.post<
        ApiResponse<{ access_token: string }>
      >('/auth/refresh');
      const newToken = response.data.data.access_token;
      this.setAccessToken(newToken);
      return newToken;
    } catch (error) {
      throw new Error('Failed to refresh token');
    }
  }

  /**
   * Set access token (called after login or refresh)
   */
  setAccessToken(token: string) {
    this.accessToken = token;
  }

  /**
   * Clear access token (called on logout)
   */
  clearAccessToken() {
    this.accessToken = null;
  }

  /**
   * Get access token
   */
  getAccessToken(): string | null {
    return this.accessToken;
  }

  /**
   * Generic GET request
   */
  async get<T>(url: string, params?: Record<string, any>): Promise<ApiResponse<T>> {
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
