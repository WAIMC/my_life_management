'use client';

import { Provider, useStore } from 'react-redux';
import { QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import { Toaster } from 'react-hot-toast';
import { makeStore } from '../redux/store';
import { setAppStore } from '@/lib/api-client';
import { initAuthManager, clearAutoRefresh } from '@/lib/authManager';
import { initializeAuth } from '@/lib/authInitializer';
import broadcastManager from '@/lib/broadcastChannelManager';
import { setAuth, setTabId, setLeaderId, setRefreshAtTime, clearAuth } from '@/redux/slices/authSlice';
import { useEffect, useMemo, useState, useRef } from 'react';
import { useRouter } from 'next/navigation';
import { useDispatch, useSelector } from 'react-redux';
import type { RootState, AppStore } from '@/redux/store';
import { setNavigateFunction, clearNavigateFunction, navigateTo } from '@/lib/navigation';
import { QueryClient } from '@tanstack/react-query';

// Create query client instance
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 30 * 1000,
      gcTime: 5 * 60 * 1000,
      retry: 1,
      refetchOnWindowFocus: true,
      refetchOnMount: false,
      refetchOnReconnect: true,
    },
  },
});

function NavigationProvider() {
  const router = useRouter();

  useEffect(() => {
    // Set navigation function for use in sagas and interceptors
    setNavigateFunction((path: string) => {
      router.push(path);
    });

    return () => {
      clearNavigateFunction();
    };
  }, [router]);

  return null;
}

function BroadcastListener() {
  const dispatch = useDispatch();
  const authState = useSelector((state: RootState) => state.auth);
  const authStateRef = useRef(authState);

  // Keep ref updated with latest authState
  useEffect(() => {
    authStateRef.current = authState;
  }, [authState]);

  useEffect(() => {
    // Initialize BroadcastChannel
    broadcastManager.initialize();

    // Listen for broadcast messages from other tabs
    broadcastManager.onMessage(async (message) => {
      switch (message.type) {
        case 'AUTH_UPDATE': {
          // Logic 10.6: Sync auth state from other tabs
          if (message.accessToken && message.refreshAtTime && message.leaderId) {
            dispatch(setAuth(message.accessToken));
            // Set own tabId, not the sender's tabId
            dispatch(setTabId(broadcastManager.getTabId()));
            dispatch(setLeaderId(message.leaderId));
            dispatch(setRefreshAtTime(message.refreshAtTime));

            // Logic 10.6: Setup auto-refresh timer if this tab is focused
            const ttl = Math.ceil((message.refreshAtTime - Date.now()) / 1000);
            if (ttl > 0 && document.hasFocus()) {
              const { setupAutoRefresh } = await import('@/lib/authManager');
              setupAutoRefresh(ttl);
            }
          }
          break;
        }
        case 'LOGOUT': {
          // Logic 10.8: Handle logout from other tabs
          dispatch(clearAuth());
          clearAutoRefresh();

          // Use client-side navigation instead of window.location
          navigateTo('/login');
          break;
        }
        case 'AUTH_REQUEST': {
          // Logic 10.2: Another tab is requesting auth state
          // Respond if we have valid auth state
          const { accessToken, refreshAtTime, leaderId } = authStateRef.current;
          console.log('[Broadcast] Received AUTH_REQUEST, current auth:', {
            hasToken: !!accessToken,
            hasRefreshTime: !!refreshAtTime,
            hasLeader: !!leaderId,
            requestId: message.requestId
          });
          if (accessToken && refreshAtTime && leaderId && message.requestId) {
            console.log('[Broadcast] Responding with auth state');
            broadcastManager.respondAuthState(
              message.requestId,
              accessToken,
              refreshAtTime,
              leaderId
            );
          } else {
            console.log('[Broadcast] Cannot respond - missing auth data');
          }
          break;
        }
        case 'TAB_FOCUS': {
          // Other tab gained focus - update leader if needed
          // This can be used to rebalance leader assignment
          break;
        }
        case 'TAB_BLUR': {
          // Other tab lost focus - update leader if needed
          break;
        }
        case 'HEARTBEAT': {
          // Update last heartbeat time for leader tracking
          if (message.leaderId === authStateRef.current.leaderId) {
            // Leader is alive - update timestamp
            console.log('[Broadcast] Heartbeat received from leader:', message.leaderId);
            broadcastManager.updateHeartbeatTimestamp(message.timestamp);
          }
          break;
        }
        case 'LEADER_ELECTED': {
          // New leader elected - update state
          if (message.newLeaderId) {
            console.log('[Broadcast] New leader elected:', message.newLeaderId);
            dispatch(setLeaderId(message.newLeaderId));
            
            // If this tab is new leader - start heartbeat & refresh timer
            const currentTabId = broadcastManager.getTabId();
            if (message.newLeaderId === currentTabId) {
              console.log('[Broadcast] This tab is the new leader - starting heartbeat and refresh');
              
              const { setupAutoRefresh } = await import('@/lib/authManager');
              const ttl = authStateRef.current.refreshAtTime 
                ? Math.ceil((authStateRef.current.refreshAtTime - Date.now()) / 1000)
                : 0;
              
              if (ttl > 0) {
                // Start heartbeat
                broadcastManager.startHeartbeat(currentTabId);
                // Setup refresh timer
                setupAutoRefresh(ttl);
              }
            }
          }
          break;
        }
        case 'REFRESH_FAIL': {
          // Leader failed to refresh - all tabs logout
          console.error('[Broadcast] Refresh failed broadcast received - logging out all tabs');
          dispatch(clearAuth());
          clearAutoRefresh();
          
          const { default: localStorageManager } = await import('@/lib/localStorageManager');
          localStorageManager.clearAuthMeta();
          
          broadcastManager.stopHeartbeat();
          navigateTo('/login');
          break;
        }
      }
    });

    return () => {
      broadcastManager.close();
    };
  }, [dispatch]);

  return null;
}

function AuthInitializer() {
  const store = useStore();

  useEffect(() => {
    // CRITICAL: Initialize BroadcastChannel BEFORE running authInitializer
    // This ensures other tabs can respond to AUTH_REQUEST
    broadcastManager.initialize();
    
    // Run auth initialization in background
    initializeAuth(store as AppStore);
  }, [store]);

  // Don't block rendering - AdminLayout and LoginPage will handle their own auth checks
  return null;
}

import { ThemeProvider } from 'next-themes';

export function Providers({ children }: { children: React.ReactNode }) {
  const store = useMemo(() => {
    const newStore = makeStore();
    setAppStore(newStore);
    initAuthManager(newStore);
    return newStore;
  }, []);

  return (
    <QueryClientProvider client={queryClient}>
      <Provider store={store}>
        <ThemeProvider
          attribute="class"
          defaultTheme="system"
          enableSystem
          disableTransitionOnChange={false}
        >
          <NavigationProvider />
          <BroadcastListener />
          <AuthInitializer />
          {children}
          <Toaster
            position="top-right"
            reverseOrder={false}
            gutter={8}
            toastOptions={{
              duration: 4000,
              style: {
                background: '#fff',
                color: '#000',
              },
              success: {
                style: {
                  background: '#ecfdf5',
                  color: '#065f46',
                  border: '1px solid #86efac',
                },
              },
              error: {
                style: {
                  background: '#fef2f2',
                  color: '#7f1d1d',
                  border: '1px solid #fca5a5',
                },
              },
            }}
          />
        </ThemeProvider>
      </Provider>
      {/* React Query Devtools - only in development */}
      {process.env.NODE_ENV === 'development' && (
        <ReactQueryDevtools initialIsOpen={false} />
      )}
    </QueryClientProvider>
  );
}

