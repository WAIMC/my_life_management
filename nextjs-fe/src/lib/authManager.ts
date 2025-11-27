import { setAuth, clearAuth, setTabId, setLeaderId, setRefreshAtTime } from '@/redux/slices/authSlice';
import { apiClient } from '@/lib/api-client';
import { REFRESH_TOKEN } from '@/constants/apiUrl';
import * as CLIENT_URL from '@/constants/clientUrl';
import type { AppStore } from '@/redux/store';
import broadcastManager from './broadcastChannelManager';
import { navigateTo } from './navigation';
import localStorageManager from './localStorageManager';

let refreshTimer: ReturnType<typeof setTimeout> | null = null;
let appStore: AppStore | null = null;
let isTabFocused = false;
let isAcquiringLeader = false; // Flag to prevent duplicate leader acquisition

/**
 * Initialize auth manager with store reference
 */
export const initAuthManager = (store: AppStore) => {
  appStore = store;
  setupFocusTracking();
};

/**
 * Setup focus tracking to monitor if current tab is focused
 */
const setupFocusTracking = () => {
  if (typeof window === 'undefined') return;

  isTabFocused = document.hasFocus();

  window.addEventListener('focus', () => {
    isTabFocused = true;
  });

  window.addEventListener('blur', () => {
    isTabFocused = false;
  });
};

/**
 * Check if current tab is focused
 */
const isCurrentTabFocused = (): boolean => {
  if (typeof window === 'undefined') return false;
  return isTabFocused;
};

/**
 * Elect a new leader tab
 * Uses Web Locks API if available, falls back to focus-based selection
 * @returns Promise<string> - ID of the elected leader
 */
export const electLeader = async (): Promise<string> => {
  if (!appStore || isAcquiringLeader) {
    return '';
  }

  isAcquiringLeader = true;

  try {
    const currentTabId = broadcastManager.getTabId();
    const isFocused = isCurrentTabFocused();
    const isVisible = typeof document !== 'undefined' && document.visibilityState === 'visible';
    // Try Web Locks API for exclusive leader lock
    if (typeof navigator !== 'undefined' && 'locks' in navigator) {
      try {
        // Check if lock is already held
        const lockState = await navigator.locks.query();
        const hasLeaderLock = lockState.held?.some(lock => lock.name === 'auth-leader-lock');

        if (!hasLeaderLock && (isFocused || isVisible)) {
          // Try to acquire lock (non-blocking request)
          await navigator.locks.request('auth-leader-lock', { mode: 'exclusive', ifAvailable: true }, async (lock) => {
            if (lock) {
              appStore!.dispatch(setLeaderId(currentTabId));

              // Start heartbeat
              broadcastManager.startHeartbeat(currentTabId);
              broadcastManager.broadcastLeaderElected(currentTabId);

              // Hold lock indefinitely (this tab is leader until closed/unfocused)
              return new Promise(() => { }); // Never resolves = holds lock
            }
            return null;
          });
        }

        return currentTabId;
      } catch (error) {
        // Fall through to fallback method
      }
    }

    // Fallback: Focus/visibility-based election
    if (isFocused || isVisible) {
      appStore.dispatch(setLeaderId(currentTabId));

      // Start heartbeat
      broadcastManager.startHeartbeat(currentTabId);
      broadcastManager.broadcastLeaderElected(currentTabId);

      return currentTabId;
    }

    // If this tab is not focused/visible, wait for another tab to claim leadership
    return '';

  } finally {
    isAcquiringLeader = false;
  }
};

/**
 * Handle heartbeat timeout - elect new leader
 */
export const handleHeartbeatTimeout = async (): Promise<void> => {
  await electLeader();
};

/**
 * Logic 3: Đồng bộ trạng thái đăng nhập giữa các tab
 * Cập nhật: access token, refresh_at_time, tab_id, leader_id
 * Enhanced với: heartbeat, localStorage persistence, leader election
 */
export const syncAuthStateAcrossTabs = async (accessToken: string, ttl: number): Promise<void> => {
  if (!appStore) {
    return;
  }

  const tabId = broadcastManager.getTabId();
  const refreshAtTime = Date.now() + (ttl - 10) * 1000;

  // Get current leader or elect new leader if needed
  let leaderId = appStore.getState().auth.leaderId;

  // If no leader exists and this tab is focused, elect this tab as leader
  if (!leaderId && isCurrentTabFocused()) {
    leaderId = await electLeader();
  } else if (!leaderId) {
    // If no leader and not focused, use current tab as temporary leader
    leaderId = tabId;
  }
  // Cập nhật Redux state
  appStore.dispatch(setAuth(accessToken));
  appStore.dispatch(setTabId(tabId));
  appStore.dispatch(setLeaderId(leaderId));
  appStore.dispatch(setRefreshAtTime(refreshAtTime));
  // Gửi broadcast để đồng bộ cho tất cả tab cùng origin
  broadcastManager.broadcastAuthUpdate(accessToken, refreshAtTime, leaderId);

  // Save metadata to localStorage for browser close/reopen scenarios
  localStorageManager.saveAuthMeta(refreshAtTime, leaderId);

  // Nếu tab hiện tại là leader: start heartbeat và timer auto refresh
  if (leaderId === tabId) {
    broadcastManager.startHeartbeat(leaderId);
    setupAutoRefresh(ttl);
  }
};

/**
 * Setup auto refresh token
 * Logic 4: Auto Refresh Token từ Timer
 * @param ttl - Token time to live in seconds
 */
export const setupAutoRefresh = (ttl: number) => {
  // Clear existing timer if any
  clearAutoRefresh();

  // Refresh token 10 seconds before expiry
  const refreshDelay = Math.max((ttl - 10) * 1000, 1000);

  refreshTimer = setTimeout(() => {
    performAutoRefresh();
  }, refreshDelay);
};

/**
 * Perform auto refresh token
 * Enhanced with: retry logic, failure broadcasting, localStorage cleanup
 */
const performAutoRefresh = async () => {
  // DISABLED: Auto refresh token logic
  return;

  /* ORIGINAL CODE - COMMENTED OUT
  if (!appStore) return;

  // Kiểm tra: tab có đang visible không?
  const isVisible = typeof document !== 'undefined' && document.visibilityState === 'visible';
  if (!isVisible && !isCurrentTabFocused()) {
    return; // Không refresh nếu tab không visible và không focus
  }

  const state = appStore.getState();
  const tabId = state.auth.tabId;
  const leaderId = state.auth.leaderId;

  // Kiểm tra: leader_id có bằng tab_id không?
  if (leaderId !== tabId) {
    return; // Không refresh nếu không phải leader
  }
  try {
    const response = await apiClient.post<{ access_token: string; ttl: number }>(REFRESH_TOKEN, {});
    const newAccessToken = response?.data?.access_token;
    const newTtl = response?.data?.ttl;

    if (!newAccessToken || !newTtl) {
      throw new Error('Invalid token response');
    }
    // Logic 3: Đồng bộ trạng thái sau khi refresh thành công
    await syncAuthStateAcrossTabs(newAccessToken, newTtl);
  } catch (error) {
    if (!appStore) return;

    // Broadcast refresh failure to all tabs
    broadcastManager.broadcastRefreshFail();

    // Save current URL for redirect after login
    const currentUrl = typeof window !== 'undefined' ? window.location.pathname : '/admin';

    // Clear auth on failure
    appStore.dispatch(clearAuth());
    clearAutoRefresh();
    broadcastManager.stopHeartbeat();
    localStorageManager.clearAuthMeta();

    // Use client-side navigation instead of window.location
    if (typeof window !== 'undefined') {
      navigateTo(`${CLIENT_URL.LOGIN}?redirect=${encodeURIComponent(currentUrl)}`);
    }
  }
  */
};

/**
 * Clear auto refresh timer
 */
export const clearAutoRefresh = () => {
  if (refreshTimer) {
    clearTimeout(refreshTimer);
    refreshTimer = null;
  }
};
