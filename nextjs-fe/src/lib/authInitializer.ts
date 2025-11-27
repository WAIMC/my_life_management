/**
 * Auth Initializer - Restore authentication state on app start
 * 
 * Follows documented flow (KEEP_SIGNIN_IN_MULTI_TAB.md line 10-14):
 * 1. Try refresh token from cookie (Logic 1)
 * 2. If fail, request from other tabs (Logic 2.1)
 * 3. If fail, state remains null -> redirect to login (Logic 2.2)
 * 
 * This is NOT about persisting state, but about restoring auth using:
 * - Refresh token in HTTP-only cookie
 * - Auth state from other active tabs via BroadcastChannel
 */

import { AppStore } from '@/redux/store';
import { apiClient } from '@/lib/api-client';
import { REFRESH_TOKEN } from '@/constants/apiUrl';
import * as CLIENT_URL from '@/constants/clientUrl';
import broadcastManager from './broadcastChannelManager';
import { syncAuthStateAcrossTabs } from './authManager';
import { setAuthInitialized } from '@/redux/slices/authSlice';

/**
 * Initialize auth state on app start
 * Should be called once when app loads
 */
export const initializeAuth = async (store: AppStore): Promise<void> => {
  // DISABLED: Auth initialization logic
  store.dispatch(setAuthInitialized(true));
  return;

  /* ORIGINAL CODE - COMMENTED OUT
  const state = store.getState();
  const { accessToken, refreshAtTime } = state.auth;
  const isOnLoginPage = typeof window !== 'undefined' && window.location.pathname === CLIENT_URL.LOGIN;

  // Logic 10.1: Check current auth state first
  // If already have valid token in state, check if redirect is needed
  if (accessToken && refreshAtTime && refreshAtTime > Date.now()) {
    // Logic 10.1: If authenticated and on login page, redirect to admin
    if (isOnLoginPage) {
      store.dispatch(setAuthInitialized(true));
      if (typeof window !== 'undefined') {
        const { navigateTo } = await import('./navigation');
        navigateTo(CLIENT_URL.ADMIN);
      }
      return;
    }

    // Already authenticated and on correct page
    store.dispatch(setAuthInitialized(true));
    return;
  }

  // Logic 10.2: No valid auth in state, try to restore from cookie or other tabs
  // Try refresh token from cookie first (Logic 10.5)
  // Skip refresh token call if on login page (per Logic 10.4)
  if (!isOnLoginPage) {
    try {
      const response = await apiClient.post<{ access_token: string; ttl: number }>(
        REFRESH_TOKEN,
        {}
      );

      if (response?.data?.access_token && response?.data?.ttl) {
        // Refresh successful - sync state across tabs
        syncAuthStateAcrossTabs(response.data.access_token, response.data.ttl);
        store.dispatch(setAuthInitialized(true));
        return;
      }
    } catch (error) {
      // Refresh failed, continue to Logic 10.2
    }
  } else {
  }

  // Logic 10.2: Request auth state from other tabs
  // CRITICAL FIX: Always try to get auth from other tabs, even if on login page
  // Tab might be on /login due to temporary redirect from AdminLayout
  try {
    const response = await broadcastManager.requestAuthState(2000);
    if (response?.accessToken && response?.refreshAtTime && response?.leaderId) {
      const ttl = Math.ceil((response.refreshAtTime - Date.now()) / 1000);
      if (ttl > 0) {
        // Got valid token from another tab
        syncAuthStateAcrossTabs(response.accessToken, ttl);
        store.dispatch(setAuthInitialized(true));

        // Logic 10.1: If authenticated and on login page, redirect to admin
        if (isOnLoginPage) {
          if (typeof window !== 'undefined') {
            const { navigateTo } = await import('./navigation');
            navigateTo(CLIENT_URL.ADMIN);
          }
        }
        return;
      } else {
      }
    } else {
    }
  } catch (error) {
    // No other tabs or failed to get auth
  }

  // NEW: Logic fallback - Check localStorage for stale auth metadata
  // This helps when browser was closed and reopened - no active tabs but cookie may still be valid
  if (!isOnLoginPage) {
    try {
      const { default: localStorageManager } = await import('./localStorageManager');
      const authMeta = localStorageManager.loadAuthMeta();

      if (authMeta && authMeta.refreshAtTime > Date.now()) {
        // LocalStorage has valid metadata - try refresh token from cookie
        try {
          const response = await apiClient.post<{ access_token: string; ttl: number }>(
            REFRESH_TOKEN,
            {}
          );

          if (response?.data?.access_token && response?.data?.ttl) {
            syncAuthStateAcrossTabs(response.data.access_token, response.data.ttl);
            store.dispatch(setAuthInitialized(true));

            // If authenticated and on login page, redirect to admin
            if (isOnLoginPage) {
              if (typeof window !== 'undefined') {
                const { navigateTo } = await import('./navigation');
                navigateTo(CLIENT_URL.ADMIN);
              }
            }
            return;
          }
        } catch (refreshError) {
          localStorageManager.clearAuthMeta();
        }
      } else if (authMeta) {
        localStorageManager.clearAuthMeta();
      }
    } catch (error) {
    }
  }

  // Logic 10.3: No auth available
  // State remains null
  // - If on login page: user will see login form (correct)
  // - If on admin page: AdminLayout will redirect to login (correct)
  store.dispatch(setAuthInitialized(true));
  */
};
