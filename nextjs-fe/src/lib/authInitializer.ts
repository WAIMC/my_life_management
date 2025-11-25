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
  // Logic 10.4: Do NOT call refresh token if on login page
  // Check page before any auth logic
  if (typeof window !== 'undefined' && window.location.pathname === CLIENT_URL.LOGIN) {
    console.log('On login page - skip auth initialization');
    store.dispatch(setAuthInitialized(true));
    return;
  }

  const state = store.getState();
  const { accessToken, refreshAtTime } = state.auth;

  // Skip if already have valid token
  if (accessToken && refreshAtTime && refreshAtTime > Date.now()) {
    store.dispatch(setAuthInitialized(true));
    return;
  }

  // Try refresh token from cookie (Logic 1)
  // This is the primary way to restore auth on reload
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
    // Refresh failed, continue to Logic 2
    console.debug('Refresh token failed on init, trying other tabs...');
  }

  // Logic 2.1: Request auth state from other tabs
  // This handles the case where another tab has valid token
  try {
    const response = await broadcastManager.requestAuthState(1000);

    if (response?.accessToken && response?.refreshAtTime && response?.leaderId) {
      const ttl = Math.ceil((response.refreshAtTime - Date.now()) / 1000);

      if (ttl > 0) {
        // Got valid token from another tab
        syncAuthStateAcrossTabs(response.accessToken, ttl);
        store.dispatch(setAuthInitialized(true));
        return;
      }
    }
  } catch (error) {
    // No other tabs or failed to get auth
    console.debug('No other tabs with valid auth');
  }

  // Logic 2.2: No auth available
  // State remains null, AdminLayout will redirect to login
  // This is the expected behavior for first-time access or expired sessions
  store.dispatch(setAuthInitialized(true));
};
