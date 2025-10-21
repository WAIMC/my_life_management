/**
 * Auth API Services
 */

import { apiPost, apiGet } from './client';
import { apiPaths } from './paths';
import { setAccessToken, setRefreshToken, clearTokens } from '../utils/token';
import type { ApiResponse, AuthTokenResponse } from '../types/api.types';

export interface LoginCredentials {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterData {
  email: string;
  user_name: string;
  password: string;
  password_confirmation: string;
  first_name: string;
  last_name: string;
  phone_number?: string;
}

export interface ResetPasswordData {
  token: string;
  email: string;
  password: string;
  password_confirmation: string;
}

/**
 * Login
 */
export const loginApi = async (credentials: LoginCredentials): Promise<ApiResponse<AuthTokenResponse>> => {
  const response = await apiPost<AuthTokenResponse>(
    apiPaths.auth.login(),
    credentials,
    { requireAuth: false }
  );

  // Save tokens if login successful
  if (!response.error.status && response.data.access_token) {
    setAccessToken(response.data.access_token);
  }

  return response;
};

/**
 * Logout
 */
export const logoutApi = async (): Promise<ApiResponse<null>> => {
  try {
    const response = await apiPost<null>(apiPaths.auth.logout());
    clearTokens();
    return response;
  } catch (error) {
    clearTokens();
    throw error;
  }
};

/**
 * Refresh Token
 */
export const refreshTokenApi = async (): Promise<ApiResponse<AuthTokenResponse>> => {
  const response = await apiPost<AuthTokenResponse>(apiPaths.auth.refresh());

  // Update access token
  if (!response.error.status && response.data.access_token) {
    setAccessToken(response.data.access_token);
  }

  return response;
};

/**
 * Register
 */
export const registerApi = async (data: RegisterData): Promise<ApiResponse<any>> => {
  return apiPost(apiPaths.auth.register(), data, { requireAuth: false });
};

/**
 * Verify Email
 */
export const verifyEmailApi = async (token: string): Promise<ApiResponse<any>> => {
  return apiPost(apiPaths.auth.verifyEmail(), { token }, { requireAuth: false });
};

/**
 * Forgot Password
 */
export const forgotPasswordApi = async (email: string): Promise<ApiResponse<any>> => {
  return apiPost(apiPaths.auth.forgotPassword(), { email }, { requireAuth: false });
};

/**
 * Reset Password
 */
export const resetPasswordApi = async (data: ResetPasswordData): Promise<ApiResponse<any>> => {
  return apiPost(apiPaths.auth.resetPassword(), data, { requireAuth: false });
};
