/**
 * Token Management Utilities
 */

import { STORAGE_KEYS } from '../constants';

/**
 * Volatile in-memory access token.
 * This token lives only in RAM and will be lost on page reload / tab close.
 * Use setVolatileAccessToken to populate it after login, and clearVolatileAccessToken to remove it.
 */
let volatileAccessToken: string | null = null;

/**
 * Get access token (volatile in-memory first, fallback to localStorage for older flows)
 */
export const getAccessToken = (): string | null => {
  if (volatileAccessToken) return volatileAccessToken;
  if (typeof window === 'undefined') return null;
  // fallback: previous persistent token (if any) — keep for backward compatibility
  return localStorage.getItem(STORAGE_KEYS.ACCESS_TOKEN);
};

/**
 * Set access token into volatile memory only.
 */
export const setVolatileAccessToken = (token: string): void => {
  volatileAccessToken = token;
};

/**
 * Clear volatile access token
 */
export const clearVolatileAccessToken = (): void => {
  volatileAccessToken = null;
};

/**
 * Compatibility alias: setAccessToken -> store access token in volatile memory
 */
export const setAccessToken = (token: string): void => {
  setVolatileAccessToken(token);
};

/**
 * Get refresh token from localStorage
 */
export const getRefreshToken = (): string | null => {
  if (typeof window === 'undefined') return null;
  return localStorage.getItem(STORAGE_KEYS.REFRESH_TOKEN);
};

/**
 * Set refresh token to localStorage (persistent)
 */
export const setRefreshToken = (token: string): void => {
  if (typeof window === 'undefined') return;
  localStorage.setItem(STORAGE_KEYS.REFRESH_TOKEN, token);
};

/**
 * Remove refresh token from localStorage
 */
export const removeRefreshToken = (): void => {
  if (typeof window === 'undefined') return;
  localStorage.removeItem(STORAGE_KEYS.REFRESH_TOKEN);
};

/**
 * Clear all tokens
 */
export const clearTokens = (): void => {
  // clear both volatile and persistent tokens
  clearVolatileAccessToken();
  removeRefreshToken();
};

/**
 * Check if user is authenticated
 */
export const isAuthenticated = (): boolean => {
  return !!getAccessToken();
};

/**
 * Decode JWT token (without verification)
 */
export const decodeToken = (token: string): Record<string, unknown> | null => {
  try {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(
      atob(base64)
        .split('')
        .map((c) => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
        .join('')
    );
    return JSON.parse(jsonPayload);
  } catch (err: unknown) {
    // Log the error for diagnosis
    console.error('Failed to decode token:', err);
    return null;
  }
};

/**
 * Check if token is expired
 */
export const isTokenExpired = (token: string): boolean => {
  try {
  const decoded = decodeToken(token);
  if (!decoded) return true;
  const exp = decoded.exp;
  if (typeof exp !== 'number') return true;

  const currentTime = Date.now() / 1000;
  return exp < currentTime;
  } catch {
    // intentionally return true on any unexpected failure
    return true;
  }
};

/**
 * Get token expiration time
 */
export const getTokenExpiration = (token: string): Date | null => {
  try {
  const decoded = decodeToken(token);
  if (!decoded) return null;
  const exp = decoded.exp;
  if (typeof exp !== 'number') return null;

  return new Date(exp * 1000);
  } catch {
    // intentionally return null on failure
    return null;
  }
};
