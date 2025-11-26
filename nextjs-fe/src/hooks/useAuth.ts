"use client";

import { useState, useCallback } from "react";
import { useAppDispatch, useAppSelector } from "@/redux/hooks";
import { setAuth, clearAuth, setAuthInitialized } from "@/redux/slices/authSlice";
import { apiClient } from "@/lib/api-client";
import { ENDPOINTS } from "@/constants/api-endpoints";
import type {
  LoginRequest,
  LoginResponse,
  AuthUser,
  ApiResponse,
} from "@/lib/types/api";
import { notification } from "@/lib/notification";

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
  const leaderId = useAppSelector((state) => state.auth.leaderId);
  const [state, setState] = useState<AuthState>({
    user: null,
    isAuthenticated: false,
    isLoading: false,
  });

  /**
   * Login with username and password
   * Logic 10.4: Call API login and sync state across tabs
   */
  const login = useCallback(
    async (username: string, password: string) => {
      try {
        setState((prev) => ({ ...prev, isLoading: true }));

        // IMPORTANT: Clear any existing cookies before login
        // Server will set new refresh token cookie on successful login
        document.cookie.split(";").forEach((c) => {
          document.cookie = c
            .replace(/^ +/, "")
            .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        });

        const response = await apiClient.post<LoginResponse>(
          ENDPOINTS.AUTH.LOGIN,
          { user_name: username, password } as LoginRequest
        );

        // Store access token
        apiClient.setAccessToken(response.data.access_token);

        // Update Redux state immediately (synchronous) to prevent race condition
        dispatch(setAuth(response.data.access_token));

        // Logic 10.6: Đồng bộ trạng thái đăng nhập giữa các tab
        // Sync auth state across all tabs via broadcast channel
        const { syncAuthStateAcrossTabs } = await import("@/lib/authManager");
        await syncAuthStateAcrossTabs(response.data.access_token, response.data.ttl);

        // Fetch user profile
        const userResponse = await apiClient.get<AuthUser>(ENDPOINTS.AUTH.ME);

        setState({
          user: userResponse.data,
          isAuthenticated: true,
          isLoading: false,
        });

        // CRITICAL: Set authInitialized to true after successful login
        // LoginPage useEffect will handle redirect when it sees authInitialized && isAuthenticated
        dispatch(setAuthInitialized(true));

        notification.success("Login successful");
      } catch (error: any) {
        setState((prev) => ({ ...prev, isLoading: false }));
        const message = error.response?.data?.message || "Login failed";
        notification.error(message);
        throw error;
      }
    },
    [dispatch]
  );

  /**
   * Logout and clear tokens
   * Logic 10.8: Đăng xuất tất cả tab cùng origin
   */
  const logout = useCallback(async () => {
    try {
      setState((prev) => ({ ...prev, isLoading: true }));

      await apiClient.post(ENDPOINTS.AUTH.LOGOUT);
      apiClient.clearAccessToken();

      // Clear Redux store
      dispatch(clearAuth());

      // Logic 10.8: Broadcast logout to all tabs
      const broadcastManager = (await import("@/lib/broadcastChannelManager"))
        .default;
      broadcastManager.broadcastLogout(leaderId || "");

      setState({
        user: null,
        isAuthenticated: false,
        isLoading: false,
      });

      notification.success("Logged out successfully");
    } catch (error: any) {
      // Clear state even if API call fails
      apiClient.clearAccessToken();
      dispatch(clearAuth());

      setState({
        user: null,
        isAuthenticated: false,
        isLoading: false,
      });

      const message = error.response?.data?.message || "Logout failed";
      notification.error(message);
    }
  }, [dispatch, leaderId]);

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
