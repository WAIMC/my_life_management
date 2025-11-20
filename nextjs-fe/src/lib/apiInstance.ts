import axios, { AxiosError, AxiosInstance, InternalAxiosRequestConfig } from 'axios';
import toast from 'react-hot-toast';
import * as API_URL from '@/constants/apiUrl';
import * as CLIENT_URL from '@/constants/clientUrl';
import { ERR_MESS } from '@/constants/messages';
import { setAuth, clearAuth } from '@/redux/slices/authSlice';
import { handleCommonError } from './apiErrorHandle';
import { ApiResponse } from '@/types/apiType';
import type { AppStore } from '../redux/store';

// Init axios instance
const axiosInstance: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:81/api',
  timeout: 30000, // 30 seconds
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Get Redux store reference (set by providers)
let appStore: AppStore | null = null;

// Queue to handle multiple requests when refresh token
let isRefreshing = false;
let refreshPromise: Promise<string | null> | null = null;

// Keep track of pending requests for refresh
const requestQueue: Array<() => void> = [];

/**
 * Check if there are other tabs with active session
 */
const hasOtherActiveTab = (): boolean => {
  if (typeof window === 'undefined') return false;

  const state = appStore?.getState();
  const tabId = state?.auth.tabId;
  const accessToken = state?.auth.accessToken;
  const refreshAtTime = state?.auth.refreshAtTime;
  const leaderId = state?.auth.leaderId;

  // Nếu có tab_id, accessToken, refreshAtTime, leaderId -> có tab hoạt động
  return !!(tabId && accessToken && refreshAtTime && leaderId);
};

// REQUEST INTERCEPTOR
axiosInstance.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    // Get access token from Redux state if store is initialized
    if (appStore) {
      const state = appStore.getState();
      const accessToken = state.auth.accessToken;

      // Add Authorization header for all requests (except refresh-token and login)
      if (accessToken && config.url !== API_URL.REFRESH_TOKEN && config.url !== API_URL.LOGIN) {
        config.headers.Authorization = `Bearer ${accessToken}`;
      }

      // Attach credentials (cookie) for necessary endpoints
      if (config.url === API_URL.REFRESH_TOKEN || config.url === API_URL.LOGOUT) {
        config.withCredentials = true;
      }
    }

    // Add cache control headers per request
    config.headers['Cache-Control'] = 'no-cache';
    config.headers['Pragma'] = 'no-cache';

    return config;
  },
  (error: AxiosError) => {
    return Promise.reject(error);
  }
);

// RESPONSE INTERCEPTOR
axiosInstance.interceptors.response.use(
  (response) => {
    return response;
  },
  async (error: AxiosError) => {
    if (!appStore) {
      return Promise.reject(error);
    }

    const originalRequest = error.config as InternalAxiosRequestConfig & { _retry?: number };

    // Check if currently on login page - do NOT refresh token per Logic 2.2
    const isOnLoginPage = typeof window !== 'undefined' && window.location.pathname === CLIENT_URL.LOGIN;

    // Handle 401 error - Unauthorized (Token expired or invalid)
    // Skip refresh if on login page or if it's already a refresh token request
    if (
      error.response?.status === 401 &&
      originalRequest &&
      !originalRequest._retry &&
      !originalRequest.url?.includes(API_URL.REFRESH_TOKEN) &&
      !originalRequest.url?.includes(API_URL.LOGIN) &&
      !isOnLoginPage  // Skip refresh if on login page per Logic 2.2
    ) {
      // Mark retry attempt
      originalRequest._retry = 1;

      if (isRefreshing && refreshPromise) {
        // If already refreshing, wait for that promise to resolve
        try {
          const newToken = await refreshPromise;
          if (newToken) {
            originalRequest.headers.Authorization = `Bearer ${newToken}`;
            return axiosInstance(originalRequest);
          } else {
            return Promise.reject(error);
          }
        } catch (err) {
          return Promise.reject(err);
        }
      }

      // Start refresh process
      isRefreshing = true;

      refreshPromise = (async () => {
        try {
          // Call API refresh token (server will read refresh token from cookie)
          const response = await axiosInstance.post<ApiResponse<{ access_token: string; ttl: number }>>(
            API_URL.REFRESH_TOKEN
          );
          // Unwrap ApiResponse structure
          const newAccessToken = response.data.data.access_token;
          const newTtl = response.data.data.ttl;

          // Update new token in Redux
          appStore!.dispatch(setAuth(newAccessToken));

          // Re-setup auto refresh with new ttl
          const { syncAuthStateAcrossTabs } = await import('./authManager');
          syncAuthStateAcrossTabs(newAccessToken, newTtl);

          // Process queued requests
          requestQueue.forEach((cb) => cb());
          requestQueue.length = 0;

          return newAccessToken;
        } catch {
          // Logic 2: Token lỗi (refresh thất bại)

          // Kiểm tra: có tab nào cùng origin đang hoạt động không?
          if (hasOtherActiveTab()) {
            // Logic 2.1: Khi mở nhiều tab, reload nhiều lần
            // Lấy thông tin từ tab khác
            const state = appStore!.getState();
            const accessToken = state.auth.accessToken;
            const refreshAtTime = state.auth.refreshAtTime;
            const leaderId = state.auth.leaderId;

            if (accessToken && refreshAtTime && leaderId) {
              // Lấy token từ tab khác
              const { syncAuthStateAcrossTabs: syncAuth } = await import('./authManager');
              const ttl = Math.ceil((refreshAtTime - Date.now()) / 1000);
              syncAuth(accessToken, Math.max(ttl, 1));

              return accessToken;
            }
          }

          // Logic 2.2: Truy cập page lần đầu hoặc không có tab nào hoạt động
          // Redirect sang login page
          appStore!.dispatch(clearAuth());

          const { clearAutoRefresh } = await import('./authManager');
          clearAutoRefresh();

          if (typeof window !== 'undefined') {
            // Save current URL for redirect after login (Logic 2.2)
            const currentUrl = window.location.pathname + window.location.search;
            toast.error(ERR_MESS.E0002);
            window.location.href = `${CLIENT_URL.LOGIN}?redirect=${encodeURIComponent(currentUrl)}`;
          }

          return null;
        } finally {
          isRefreshing = false;
          refreshPromise = null;
        }
      })();

      const newToken = await refreshPromise;
      if (newToken) {
        originalRequest.headers.Authorization = `Bearer ${newToken}`;
        return axiosInstance(originalRequest);
      } else {
        return Promise.reject(error);
      }
    }

    // Handle other errors
    return handleCommonError(error as AxiosError<ApiResponse<unknown>>);
  }
);

export default axiosInstance;

// Export store setter for initialization in providers
export const setAppStore = (store: AppStore) => {
  appStore = store;
};
