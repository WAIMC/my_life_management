// lib/api.ts
import axios, { AxiosRequestConfig, AxiosResponse, AxiosError } from 'axios';
import { makeStore } from '../redux/store';
import { refreshSaga } from '../redux/sagas/authSaga';
import { runSaga } from 'redux-saga';
import toast from 'react-hot-toast';

// Helper: Set Auth Bearer from Redux state
export const setAuthHeader = (config: AxiosRequestConfig): AxiosRequestConfig => {
  const state = makeStore().getState();
  const accessToken = state.auth.accessToken;
  if (accessToken) {
    config.headers = {
      ...config.headers,
      Authorization: `Bearer ${accessToken}`,
    };
  }
  return config;
};

// Helper: Set Content-Type (default JSON, can override)
export const setContentType = (config: AxiosRequestConfig, contentType: string = 'application/json'): AxiosRequestConfig => {
  config.headers = {
    ...config.headers,
    'Content-Type': contentType,
  };
  return config;
};

// Helper: Set no-cache
export const setNoCache = (config: AxiosRequestConfig): AxiosRequestConfig => {
  config.headers = {
    ...config.headers,
    'Cache-Control': 'no-cache',
    Pragma: 'no-cache',
    Expires: '0',
  };
  return config;
};

// Helper: Encode body (default JSON stringify, or custom for form)
export const encodeBody = (body: Record<string, unknown>, contentType: string = 'application/json'): string | URLSearchParams | Record<string, unknown> => {
  if (contentType === 'application/json') {
    return JSON.stringify(body);
  } else if (contentType === 'application/x-www-form-urlencoded') {
    const params = new URLSearchParams();
    for (const key in body) {
      params.append(key, String(body[key]));
    }
    return params;
  }
  return body;
};

// Helper: Check if sent cookie (only for /refresh-token or /logout)
export const shouldSendCookie = (url: string): boolean => {
  return url.includes('/refresh-token') || url.includes('/logout');
};

// Helper: Handle error with switch status
export const handleApiError = async (error: AxiosError, originalConfig?: AxiosRequestConfig): Promise<AxiosResponse | never> => {
  if (!error.response) {
    toast.error('Network error: Please check your connection.');
    throw error;
  }

  const status = error?.status || error?.response?.status || null;

  switch (status) {
    case 401: // Auto refresh + retry
      try {
        const { accessToken } = await new Promise<{ accessToken: string }>((resolve, reject) => {
          runSaga({}, refreshSaga).toPromise().then(resolve).catch(reject);
        });
        // Update config và retry
        if (originalConfig) {
          originalConfig.headers = {
            ...originalConfig.headers,
            Authorization: `Bearer ${accessToken}`,
          };
          return axios.request(originalConfig); // Retry request gốc
        }
      } catch (refreshError) {
        // Fail: Redirect login
        if (typeof window !== 'undefined') {
          window.location.href = '/login';
        }
        throw refreshError;
      }
      break;
    case 403:
      toast.error('Access forbidden: Permission denied.');
      break;
    case 404:
      toast.error('Resource not found.');
      break;
    case 500:
      toast.error('Internal server error: Please try again later.');
      break;
    case 503:
      toast.error('Service unavailable: Server is down.');
      break;
    default:
      toast.error(`Error ${status}: ${error.message}`);
  }

  throw error;
};

// Custom API client with methods (apply helpers)
const api = {
  get: async (url: string, config: AxiosRequestConfig = {}) => {
    config = setAuthHeader(config);
    config = setContentType(config);
    config = setNoCache(config);
    config.withCredentials = shouldSendCookie(url);

    try {
      const response = await axios.get(`${baseURL}${url}`, config);
      return response;
    } catch (error) {
      return handleApiError(error as AxiosError, { ...config, method: 'get', url: `${baseURL}${url}` });
    }
  },

  post: async (url: string, body: Record<string, unknown>, config: AxiosRequestConfig = {}) => {
    const contentType = config.headers?.['Content-Type'] || 'application/json';
    const encodedBody = encodeBody(body, contentType);
    config = setAuthHeader(config);
    config = setContentType(config, contentType);
    config = setNoCache(config);
    config.withCredentials = shouldSendCookie(url);

    try {
      const response = await axios.post(`${baseURL}${url}`, encodedBody, config);
      return response;
    } catch (error) {
      return handleApiError(error as AxiosError, { ...config, method: 'post', url: `${baseURL}${url}`, data: encodedBody });
    }
  },

  put: async (url: string, body: Record<string, unknown>, config: AxiosRequestConfig = {}) => {
    const contentType = config.headers?.['Content-Type'] || 'application/json';
    const encodedBody = encodeBody(body, contentType);
    config = setAuthHeader(config);
    config = setContentType(config, contentType);
    config = setNoCache(config);
    config.withCredentials = shouldSendCookie(url);

    try {
      const response = await axios.put(`${baseURL}${url}`, encodedBody, config);
      return response;
    } catch (error) {
      return handleApiError(error as AxiosError, { ...config, method: 'put', url: `${baseURL}${url}`, data: encodedBody });
    }
  },

  patch: async (url: string, body: Record<string, unknown>, config: AxiosRequestConfig = {}) => {
    const contentType = config.headers?.['Content-Type'] || 'application/json';
    const encodedBody = encodeBody(body, contentType);
    config = setAuthHeader(config);
    config = setContentType(config, contentType);
    config = setNoCache(config);
    config.withCredentials = shouldSendCookie(url);

    try {
      const response = await axios.patch(`${baseURL}${url}`, encodedBody, config);
      return response;
    } catch (error) {
      return handleApiError(error as AxiosError, { ...config, method: 'patch', url: `${baseURL}${url}`, data: encodedBody });
    }
  },

  delete: async (url: string, config: AxiosRequestConfig = {}) => {
    config = setAuthHeader(config);
    config = setContentType(config);
    config = setNoCache(config);
    config.withCredentials = shouldSendCookie(url);

    try {
      const response = await axios.delete(`${baseURL}${url}`, config);
      return response;
    } catch (error) {
      return handleApiError(error as AxiosError, { ...config, method: 'delete', url: `${baseURL}${url}` });
    }
  },
};

const baseURL = process.env.API_URL

export default api;
