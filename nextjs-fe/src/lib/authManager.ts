import { setAuth, clearAuth, setTabId, setLeaderId, setRefreshAtTime } from '@/redux/slices/authSlice';
import { apiPost } from './apiMethod';
import { REFRESH_TOKEN } from '@/constants/apiUrl';
import * as CLIENT_URL from '@/constants/clientUrl';
import type { AppStore } from '@/redux/store';
import broadcastManager from './broadcastChannelManager';
import { navigateTo } from './navigation';

let refreshTimer: NodeJS.Timeout | null = null;
let appStore: AppStore | null = null;
let isTabFocused = false;

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
 * Logic 3: Đồng bộ trạng thái đăng nhập giữa các tab
 * Cập nhật: access token, refresh_at_time, tab_id, leader_id
 */
export const syncAuthStateAcrossTabs = (accessToken: string, ttl: number) => {
  if (!appStore) return;

  const tabId = broadcastManager.getTabId();
  const refreshAtTime = Date.now() + (ttl - 10) * 1000;

  // Xác định leader_id: nếu tab hiện tại focus thì trở thành leader
  const leaderId = isCurrentTabFocused() ? tabId : appStore.getState().auth.leaderId || tabId;

  // Cập nhật Redux state
  appStore.dispatch(setAuth(accessToken));
  appStore.dispatch(setTabId(tabId));
  appStore.dispatch(setLeaderId(leaderId));
  appStore.dispatch(setRefreshAtTime(refreshAtTime));

  // Gửi broadcast để đồng bộ cho tất cả tab cùng origin
  broadcastManager.broadcastAuthUpdate(accessToken, refreshAtTime, leaderId);

  // Nếu tab hiện tại focus: tạo timer auto refresh
  if (isCurrentTabFocused()) {
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
 */
const performAutoRefresh = async () => {
  if (!appStore) return;

  // Kiểm tra: tab có đang focus không?
  if (!isCurrentTabFocused()) {
    return; // Không refresh nếu tab không focus
  }

  const state = appStore.getState();
  const tabId = state.auth.tabId;
  const leaderId = state.auth.leaderId;

  // Kiểm tra: leader_id có bằng tab_id không?
  if (leaderId !== tabId) {
    return; // Không refresh nếu không phải leader
  }

  try {
    const response = await apiPost<{ access_token: string; ttl: number }>(REFRESH_TOKEN, {});
    const newAccessToken = response?.access_token;
    const newTtl = response?.ttl;

    if (!newAccessToken || !newTtl) {
      throw new Error('Invalid token response');
    }

    // Logic 3: Đồng bộ trạng thái sau khi refresh thành công
    syncAuthStateAcrossTabs(newAccessToken, newTtl);
  } catch {
    if (!appStore) return;

    // Save current URL for redirect after login
    const currentUrl = typeof window !== 'undefined' ? window.location.pathname : '/admin';

    // Clear auth on failure
    appStore.dispatch(clearAuth());
    clearAutoRefresh();

    // Use client-side navigation instead of window.location
    if (typeof window !== 'undefined') {
      navigateTo(`${CLIENT_URL.LOGIN}?redirect=${encodeURIComponent(currentUrl)}`);
    }
  }
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
