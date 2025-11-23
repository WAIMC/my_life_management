import axios, { AxiosError, AxiosInstance, InternalAxiosRequestConfig } from 'axios';
import toast from 'react-hot-toast';
import * as API_URL from '@/constants/apiUrl';
import * as CLIENT_URL from '@/constants/clientUrl';
import { ERR_MESS } from '@/constants/messages';
import { setAuth, clearAuth } from '@/redux/slices/authSlice';
import { handleCommonError } from './apiErrorHandle';
import { ApiResponse } from '@/types/apiType';
import type { AppStore } from '../redux/store';
import { navigateTo } from './navigation';

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

          // Logic 1: Token hết hạn - refresh thành công
          // Update new token in Redux
          appStore!.dispatch(setAuth(newAccessToken));

          // Logic 3: Đồng bộ trạng thái đăng nhập
          const { syncAuthStateAcrossTabs } = await import('./authManager');
          syncAuthStateAcrossTabs(newAccessToken, newTtl);

          // Process queued requests
          requestQueue.forEach((cb) => cb());
          requestQueue.length = 0;

          return newAccessToken;
        } catch (refreshError) {
          // Logic 2: Token lỗi (refresh thất bại)
          
          // Logic 2.1: Kiểm tra có tab nào cùng origin đang hoạt động không?
          // GỬI REQUEST qua BroadcastChannel để lấy auth từ tab khác
          try {
            const broadcastManager = (await import('./broadcastChannelManager')).default;
            const response = await broadcastManager.requestAuthState(1000);
            
            if (response && response.accessToken && response.refreshAtTime && response.leaderId) {
              // Nhận được token từ tab khác
              const ttl = Math.ceil((response.refreshAtTime - Date.now()) / 1000);
              
              if (ttl > 0) {
                // Token từ tab khác còn hợp lệ
                const { syncAuthStateAcrossTabs } = await import('./authManager');
                syncAuthStateAcrossTabs(response.accessToken, ttl);
                
                return response.accessToken;
              }
            }
          } catch (broadcastError) {
            console.warn('Failed to get auth from other tabs:', broadcastError);
          }

          // Logic 2.2: Không có tab nào phản hồi hoặc token từ tab khác cũng hết hạn
          // Redirect sang login page
          appStore!.dispatch(clearAuth());

          const { clearAutoRefresh } = await import('./authManager');
          clearAutoRefresh();

          if (typeof window !== 'undefined') {
            // Save current URL for redirect after login (Logic 2.2)
            const currentUrl = window.location.pathname + window.location.search;
            toast.error(ERR_MESS.E0002);
            // Use client-side navigation instead of window.location
            navigateTo(`${CLIENT_URL.LOGIN}?redirect=${encodeURIComponent(currentUrl)}`);
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
