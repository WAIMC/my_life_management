/**
 * LocalStorage Manager - Persist auth metadata for browser close/reopen scenarios
 * 
 * Security Note: Only stores metadata (refreshAtTime, leaderId), NOT sensitive tokens.
 * Tokens remain in memory/Redux state only.
 * 
 * Purpose:
 * - Save auth state metadata to survive browser close
 * - Provide fallback when no other tabs are active
 * - Auto-expire based on refresh token TTL
 */

const AUTH_META_KEY = '__auth_meta__';

// Refresh token TTL - 7 days in milliseconds
// This should match your backend refresh token expiration
const REFRESH_TOKEN_TTL_MS = 7 * 24 * 60 * 60 * 1000;

export interface AuthMeta {
  refreshAtTime: number;  // When access token should be refreshed (ms timestamp)
  leaderId: string;       // Last known leader tab ID
  savedAt: number;        // When this metadata was saved (ms timestamp)
}

/**
 * LocalStorage Manager for auth metadata
 */
export const localStorageManager = {
  /**
   * Save auth metadata to localStorage
   * Called after successful login or token refresh
   */
  saveAuthMeta(refreshAtTime: number, leaderId: string): void {
    if (typeof window === 'undefined') return;

    try {
      const meta: AuthMeta = {
        refreshAtTime,
        leaderId,
        savedAt: Date.now(),
      };

      localStorage.setItem(AUTH_META_KEY, JSON.stringify(meta));
      console.log('[LocalStorage] Saved auth meta:', {
        refreshAtTime: new Date(refreshAtTime).toISOString(),
        leaderId,
      });
    } catch (error) {
      console.error('[LocalStorage] Failed to save auth meta:', error);
    }
  },

  /**
   * Load auth metadata from localStorage
   * Returns null if not found, expired, or invalid
   */
  loadAuthMeta(): AuthMeta | null {
    if (typeof window === 'undefined') return null;

    try {
      const raw = localStorage.getItem(AUTH_META_KEY);
      if (!raw) {
        console.log('[LocalStorage] No auth meta found');
        return null;
      }

      const meta: AuthMeta = JSON.parse(raw);

      // Validate required fields
      if (!meta.refreshAtTime || !meta.leaderId || !meta.savedAt) {
        console.warn('[LocalStorage] Invalid auth meta - missing fields');
        this.clearAuthMeta();
        return null;
      }

      // Check if metadata has expired based on refresh token TTL
      const age = Date.now() - meta.savedAt;
      if (age > REFRESH_TOKEN_TTL_MS) {
        console.log('[LocalStorage] Auth meta expired (age: %dms, TTL: %dms)', age, REFRESH_TOKEN_TTL_MS);
        this.clearAuthMeta();
        return null;
      }

      // Check if refresh time has already passed
      // If so, metadata is stale but may still be useful for refresh attempt
      const timeUntilRefresh = meta.refreshAtTime - Date.now();
      if (timeUntilRefresh < -60000) {
        // More than 1 minute past refresh time - likely stale
        console.warn('[LocalStorage] Auth meta is stale (refresh time was %dms ago)', -timeUntilRefresh);
        // Don't clear - let caller try refresh and handle failure
      }

      console.log('[LocalStorage] Loaded auth meta:', {
        refreshAtTime: new Date(meta.refreshAtTime).toISOString(),
        leaderId: meta.leaderId,
        age: `${Math.round(age / 1000)}s`,
      });

      return meta;
    } catch (error) {
      console.error('[LocalStorage] Failed to load auth meta:', error);
      this.clearAuthMeta();
      return null;
    }
  },

  /**
   * Clear auth metadata from localStorage
   * Called on logout or when metadata becomes invalid
   */
  clearAuthMeta(): void {
    if (typeof window === 'undefined') return;

    try {
      localStorage.removeItem(AUTH_META_KEY);
      console.log('[LocalStorage] Cleared auth meta');
    } catch (error) {
      console.error('[LocalStorage] Failed to clear auth meta:', error);
    }
  },

  /**
   * Check if auth metadata exists and is valid (not expired)
   * Useful for quick checks without loading full metadata
   */
  hasValidAuthMeta(): boolean {
    const meta = this.loadAuthMeta();
    return meta !== null;
  },
};

export default localStorageManager;
