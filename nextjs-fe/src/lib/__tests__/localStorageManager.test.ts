import { describe, it, expect, beforeEach, vi } from 'vitest';
import { localStorageManager, AuthMeta } from '../localStorageManager';

describe('localStorageManager', () => {
  beforeEach(() => {
    // Clear localStorage before each test
    localStorage.clear();
    vi.clearAllMocks();
  });

  describe('saveAuthMeta', () => {
    it('should save auth metadata to localStorage', () => {
      const refreshAtTime = Date.now() + 3600000; // 1 hour from now
      const leaderId = 'tab_123';

      localStorageManager.saveAuthMeta(refreshAtTime, leaderId);

      const raw = localStorage.getItem('__auth_meta__');
      expect(raw).toBeTruthy();

      const meta: AuthMeta = JSON.parse(raw!);
      expect(meta.refreshAtTime).toBe(refreshAtTime);
      expect(meta.leaderId).toBe(leaderId);
      expect(meta.savedAt).toBeGreaterThan(0);
    });

    it('should overwrite existing auth metadata', () => {
      // Save first metadata
      localStorageManager.saveAuthMeta(Date.now() + 1000, 'tab_1');

      // Save second metadata
      const refreshAtTime = Date.now() + 2000;
      localStorageManager.saveAuthMeta(refreshAtTime, 'tab_2');

      const meta = localStorageManager.loadAuthMeta();
      expect(meta?.leaderId).toBe('tab_2');
      expect(meta?.refreshAtTime).toBe(refreshAtTime);
    });
  });

  describe('loadAuthMeta', () => {
    it('should load valid auth metadata', () => {
      const refreshAtTime = Date.now() + 3600000;
      const leaderId = 'tab_123';

      localStorageManager.saveAuthMeta(refreshAtTime, leaderId);
      const loaded = localStorageManager.loadAuthMeta();

      expect(loaded).not.toBeNull();
      expect(loaded?.refreshAtTime).toBe(refreshAtTime);
      expect(loaded?.leaderId).toBe(leaderId);
    });

    it('should return null if no metadata exists', () => {
      const loaded = localStorageManager.loadAuthMeta();
      expect(loaded).toBeNull();
    });

    it('should return null and clear if metadata is expired', () => {
      // Create metadata that expired 8 days ago
      const expiredMeta: AuthMeta = {
        refreshAtTime: Date.now() + 1000,
        leaderId: 'tab_expired',
        savedAt: Date.now() - (8 * 24 * 60 * 60 * 1000), // 8 days ago
      };

      localStorage.setItem('__auth_meta__', JSON.stringify(expiredMeta));

      const loaded = localStorageManager.loadAuthMeta();
      expect(loaded).toBeNull();

      // Should also clear from localStorage
      const raw = localStorage.getItem('__auth_meta__');
      expect(raw).toBeNull();
    });

    it('should return metadata even if refresh time has passed (for retry)', () => {
      // Metadata with refresh time in the past (but not expired by TTL)
      const meta: AuthMeta = {
        refreshAtTime: Date.now() - 30000, // 30 seconds ago
        leaderId: 'tab_stale',
        savedAt: Date.now() - 60000, // saved 1 minute ago
      };

      localStorage.setItem('__auth_meta__', JSON.stringify(meta));

      const loaded = localStorageManager.loadAuthMeta();
      // Should still return it (caller can decide to refresh)
      expect(loaded).not.toBeNull();
      expect(loaded?.leaderId).toBe('tab_stale');
    });

    it('should return null if metadata is corrupted', () => {
      localStorage.setItem('__auth_meta__', 'invalid json');

      const loaded = localStorageManager.loadAuthMeta();
      expect(loaded).toBeNull();

      // Should clear corrupted data
      const raw = localStorage.getItem('__auth_meta__');
      expect(raw).toBeNull();
    });

    it('should return null if metadata is missing required fields', () => {
      const invalidMeta = {
        refreshAtTime: Date.now(),
        // missing leaderId and savedAt
      };

      localStorage.setItem('__auth_meta__', JSON.stringify(invalidMeta));

      const loaded = localStorageManager.loadAuthMeta();
      expect(loaded).toBeNull();
    });
  });

  describe('clearAuthMeta', () => {
    it('should clear auth metadata from localStorage', () => {
      localStorageManager.saveAuthMeta(Date.now() + 1000, 'tab_123');

      expect(localStorage.getItem('__auth_meta__')).toBeTruthy();

      localStorageManager.clearAuthMeta();

      expect(localStorage.getItem('__auth_meta__')).toBeNull();
    });

    it('should not throw if metadata does not exist', () => {
      expect(() => localStorageManager.clearAuthMeta()).not.toThrow();
    });
  });

  describe('hasValidAuthMeta', () => {
    it('should return true if valid metadata exists', () => {
      localStorageManager.saveAuthMeta(Date.now() + 1000, 'tab_123');

      expect(localStorageManager.hasValidAuthMeta()).toBe(true);
    });

    it('should return false if no metadata exists', () => {
      expect(localStorageManager.hasValidAuthMeta()).toBe(false);
    });

    it('should return false if metadata is expired', () => {
      const expiredMeta: AuthMeta = {
        refreshAtTime: Date.now(),
        leaderId: 'tab_expired',
        savedAt: Date.now() - (8 * 24 * 60 * 60 * 1000), // 8 days ago
      };

      localStorage.setItem('__auth_meta__', JSON.stringify(expiredMeta));

      expect(localStorageManager.hasValidAuthMeta()).toBe(false);
    });
  });
});
