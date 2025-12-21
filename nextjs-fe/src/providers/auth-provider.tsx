"use client";

import React, { useEffect, useState, useCallback, useRef } from "react";
import { AuthContext } from "@/contexts/auth-context";
import { authService } from "@/services/auth.service";
import {
  AUTH_CHANNEL_NAME,
  AuthMessage,
  LoginSuccessPayload,
  RefreshSuccessPayload,
} from "@/lib/broadcast";
import { useRouter } from "next/navigation";

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<any | null>(null);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  const [expiresAt, setExpiresAt] = useState<number | null>(null);

  const router = useRouter();
  const timerRef = useRef<NodeJS.Timeout | null>(null);
  const channelRef = useRef<BroadcastChannel | null>(null);

  // =================================================================
  // BROADCAST CHANNEL SETUP
  // =================================================================
  useEffect(() => {
    if (typeof window === "undefined") return;

    const channel = new BroadcastChannel(AUTH_CHANNEL_NAME);
    channelRef.current = channel;

    channel.onmessage = (event: MessageEvent<AuthMessage>) => {
      const { type, payload } = event.data;

      switch (type) {
        case "LOGIN_SUCCESS":
          handleLoginSuccess(payload as LoginSuccessPayload);
          break;
        case "REFRESH_SUCCESS":
          handleRefreshSuccess(payload as RefreshSuccessPayload);
          break;
        case "LOGOUT":
          handleLogoutSync();
          break;
      }
    };

    return () => {
      channel.close();
    };
  }, []);

  // =================================================================
  // TIMER LOGIC
  // =================================================================
  useEffect(() => {
    if (timerRef.current) {
      clearTimeout(timerRef.current);
      timerRef.current = null;
    }

    if (!expiresAt || !isAuthenticated) return;

    const now = Date.now();
    const expirationTime = expiresAt * 1000;
    // Schedule check 30 seconds before expiration
    const timeUntilCheck = expirationTime - now - 30000;

    if (timeUntilCheck <= 0) {
      // If already past the check time (but technically not expired or just expired),
      // trigger check immediately
      handleScheduledRefresh();
    } else {
      timerRef.current = setTimeout(handleScheduledRefresh, timeUntilCheck);
    }

    return () => {
      if (timerRef.current) clearTimeout(timerRef.current);
    };
  }, [expiresAt, isAuthenticated]);

  // =================================================================
  // REFRESH FLOW (LEADER LOGIC)
  // =================================================================
  const handleScheduledRefresh = async () => {
    // 1. Check if another tab is already refreshing
    if (authService.isRefreshLocked()) {
      return; // Wait for broadcast
    }

    // 2. Try to become leader
    if (await authService.acquireRefreshLock()) {
      try {
        // 3. Call Refresh API
        const data = await authService.refreshToken();

        // 4. Update Local State
        setExpiresAt(data.expires_at);

        // 5. Broadcast Success
        broadcast("REFRESH_SUCCESS", { expiresAt: data.expires_at });
      } catch (error) {
        console.error("Refresh failed:", error);
        // 6. On Failure -> Logout All
        await performLogout();
      } finally {
        // 7. Release Lock
        authService.releaseRefreshLock();
      }
    }
  };

  // =================================================================
  // HANDLERS
  // =================================================================

  const broadcast = (type: any, payload?: any) => {
    channelRef.current?.postMessage({ type, payload });
  };

  const handleLoginSuccess = (payload: LoginSuccessPayload) => {
    setUser(payload.user);
    setExpiresAt(payload.expiresAt);
    setIsAuthenticated(true);
  };

  const handleRefreshSuccess = (payload: RefreshSuccessPayload) => {
    setExpiresAt(payload.expiresAt);
  };

  const handleLogoutSync = () => {
    setUser(null);
    setExpiresAt(null);
    setIsAuthenticated(false);
    router.push("/login");
  };

  const performLogout = async () => {
    try {
      await authService.logout();
    } catch (e) {
      // Ignore errors during logout
    } finally {
      handleLogoutSync();
      broadcast("LOGOUT");
    }
  };

  // =================================================================
  // PUBLIC METHODS
  // =================================================================

  const login = async (credentials: any) => {
    const data = await authService.login(credentials);

    // Update Local
    setUser(data.user || {}); // Adjust based on actual API response
    setExpiresAt(data.expires_at);
    setIsAuthenticated(true);

    // Broadcast
    broadcast("LOGIN_SUCCESS", {
      expiresAt: data.expires_at,
      user: data.user,
    });
  };

  const logout = async () => {
    await performLogout();
  };

  // =================================================================
  // INITIALIZATION
  // =================================================================
  useEffect(() => {
    const initAuth = async () => {
      try {
        const data = await authService.getMe();
        setUser(data.user || data);
        setExpiresAt(data.expires_at);
        setIsAuthenticated(true);
      } catch (error) {
        // Not authenticated, just stay in guest mode
        setIsAuthenticated(false);
      } finally {
        setIsLoading(false);
      }
    };

    initAuth();
  }, []);

  return (
    <AuthContext.Provider
      value={{ user, isAuthenticated, isLoading, login, logout }}
    >
      {children}
    </AuthContext.Provider>
  );
}
