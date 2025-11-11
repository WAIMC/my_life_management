import axios, { AxiosError, AxiosInstance, InternalAxiosRequestConfig } from 'axios';
import { makeStore } from '../redux/store';
import toast from 'react-hot-toast';
import * as API_URL from '@/constants/apiUrl';
import * as CLIENT_URL from '@/constants/clientUrl';
import { ERR_MESS } from '@/constants/messages';
import { setAuth, clearAuth } from '@/redux/slices/authSlice';
import { handleCommonError } from './apiErrorHandle';
import { ErrorResponse } from '@/types/apiType';

// Init axios instance
const axiosInstance: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'https://api.example.com',
  timeout: 30000, // 30 seconds
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Get Redux store once to avoid repeated initialization
let appStore = makeStore();

// Queue to handle multiple requests when refresh token
let isRefreshing = false;
let refreshPromise: Promise<string | null> | null = null;

// Keep track of pending requests for refresh
const requestQueue: Array<() => void> = [];

// REQUEST INTERCEPTOR
axiosInstance.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    // Get access token from Redux state
    const state = appStore.getState();
    const accessToken = state.auth.accessToken;

    // Add Authorization header for all requests (except refresh-token)
    if (accessToken && config.url !== API_URL.REFRESH_TOKEN) {
      config.headers.Authorization = `Bearer ${accessToken}`;
    }

    // Attach credentials (cookie) for necessary endpoints
    if (config.url === API_URL.REFRESH_TOKEN || config.url === API_URL.LOGOUT) {
      config.withCredentials = true;
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
    const originalRequest = error.config as InternalAxiosRequestConfig & { _retry?: number };

    // Handle 401 error - Unauthorized (Token expired)
    if (error.response?.status === 401 && originalRequest && !originalRequest._retry) {
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
          const response = await axiosInstance.post(API_URL.REFRESH_TOKEN);
          const newAccessToken = response.data.accessToken;

          // Update new token in Redux
          appStore.dispatch(setAuth(newAccessToken));

          // Process queued requests
          requestQueue.forEach(cb => cb());
          requestQueue.length = 0;

          return newAccessToken;
        } catch {
          // Refresh token failed -> Redirect to login page
          appStore.dispatch(clearAuth());
          
          if (typeof window !== 'undefined') {
            toast.error(ERR_MESS.E0002);
            window.location.href = CLIENT_URL.LOGIN;
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
    return handleCommonError(error as AxiosError<ErrorResponse>);
  }
);

export default axiosInstance;

// Export store setter for initialization in root layout if needed
export const updateAppStore = (store: ReturnType<typeof makeStore>) => {
  appStore = store;
};
