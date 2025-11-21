'use client';

import { useState, useCallback } from 'react';
import { useAppDispatch } from '@/redux/hooks';
import { setAuth, clearAuth } from '@/redux/slices/authSlice';
import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type {
  LoginRequest,
  LoginResponse,
  AuthUser,
  ApiResponse,
} from '@/lib/types/api';
import { notification } from '@/lib/notification';

interface AuthState {
  user: AuthUser | null;
  isAuthenticated: boolean;
  isLoading: boolean;
}

/**
 * Authentication hook
 * Manages login, logout, and user state
 */
export function useAuth() {
  const dispatch = useAppDispatch();
  const [state, setState] = useState<AuthState>({
    user: null,
    isAuthenticated: false,
    isLoading: false,
  });

  /**
   * Login with username and password
   */
  const login = useCallback(async (username: string, password: string) => {
    try {
      setState((prev) => ({ ...prev, isLoading: true }));

      const response = await apiClient.post<LoginResponse>(
        ENDPOINTS.AUTH.LOGIN,
        { user_name: username, password } as LoginRequest
      );

      // Store access token
      apiClient.setAccessToken(response.data.access_token);
      
      // Update Redux store for AdminLayout
      dispatch(setAuth(response.data.access_token));

      // Fetch user profile
      const userResponse = await apiClient.get<AuthUser>(ENDPOINTS.AUTH.ME);

      setState({
        user: userResponse.data,
        isAuthenticated: true,
        isLoading: false,
      });

      notification.success('Login successful');
    } catch (error: any) {
      setState((prev) => ({ ...prev, isLoading: false }));
      const message = error.response?.data?.message || 'Login failed';
      notification.error(message);
      throw error;
    }
  }, [dispatch]);

  /**
   * Logout and clear tokens
   */
  const logout = useCallback(async () => {
    try {
      setState((prev) => ({ ...prev, isLoading: true }));

      await apiClient.post(ENDPOINTS.AUTH.LOGOUT);
      apiClient.clearAccessToken();
      
      // Clear Redux store
      dispatch(clearAuth());

      setState({
        user: null,
        isAuthenticated: false,
        isLoading: false,
      });

      notification.success('Logged out successfully');
    } catch (error: any) {
      // Clear state even if API call fails
      apiClient.clearAccessToken();
      dispatch(clearAuth());
      
      setState({
        user: null,
        isAuthenticated: false,
        isLoading: false,
      });

      const message = error.response?.data?.message || 'Logout failed';
      notification.error(message);
    }
  }, [dispatch]);

  /**
   * Refresh access token
   */
  const refreshToken = useCallback(async () => {
    try {
      const response = await apiClient.post<LoginResponse>(
        ENDPOINTS.AUTH.REFRESH
      );
      apiClient.setAccessToken(response.data.access_token);
    } catch (error) {
      // If refresh fails, logout
      await logout();
      throw error;
    }
  }, [logout]);

  /**
   * Check if user is authenticated (call on app init)
   */
  const checkAuth = useCallback(async () => {
    try {
      setState((prev) => ({ ...prev, isLoading: true }));

      const userResponse = await apiClient.get<AuthUser>(ENDPOINTS.AUTH.ME);

      setState({
        user: userResponse.data,
        isAuthenticated: true,
        isLoading: false,
      });
    } catch (error) {
      setState({
        user: null,
        isAuthenticated: false,
        isLoading: false,
      });
    }
  }, []);

  return {
    user: state.user,
    isAuthenticated: state.isAuthenticated,
    isLoading: state.isLoading,
    login,
    logout,
    refreshToken,
    checkAuth,
  };
}
