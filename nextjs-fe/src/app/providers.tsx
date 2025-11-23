'use client';

import { Provider, useStore } from 'react-redux';
import { Toaster } from 'react-hot-toast';
import { makeStore } from '../redux/store';
import { setAppStore } from '@/lib/apiInstance';
import { initAuthManager, clearAutoRefresh } from '@/lib/authManager';
import { initializeAuth } from '@/lib/authInitializer';
import broadcastManager from '@/lib/broadcastChannelManager';
import { setAuth, setTabId, setLeaderId, setRefreshAtTime, clearAuth } from '@/redux/slices/authSlice';
import { useEffect, useMemo, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useDispatch, useSelector } from 'react-redux';
import type { RootState, AppStore } from '@/redux/store';
import { setNavigateFunction, clearNavigateFunction, navigateTo } from '@/lib/navigation';

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

  useEffect(() => {
    // Initialize BroadcastChannel
    broadcastManager.initialize();

    // Listen for broadcast messages from other tabs
    broadcastManager.onMessage((message) => {
      switch (message.type) {
        case 'AUTH_UPDATE': {
          // Sync auth state from other tabs
          if (message.accessToken && message.refreshAtTime && message.leaderId) {
            dispatch(setAuth(message.accessToken));
            dispatch(setTabId(message.tabId));
            dispatch(setLeaderId(message.leaderId));
            dispatch(setRefreshAtTime(message.refreshAtTime));
          }
          break;
        }
        case 'LOGOUT': {
          // Handle logout from other tabs
          dispatch(clearAuth());
          clearAutoRefresh();

          // Use client-side navigation instead of window.location
          navigateTo('/login');
          break;
        }
        case 'AUTH_REQUEST': {
          // Another tab is requesting auth state
          // Respond if we have valid auth state
          const { accessToken, refreshAtTime, leaderId } = authState;
          if (accessToken && refreshAtTime && leaderId && message.requestId) {
            broadcastManager.respondAuthState(
              message.requestId,
              accessToken,
              refreshAtTime,
              leaderId
            );
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
      }
    });

    return () => {
      broadcastManager.close();
    };
  }, [dispatch, authState]);

  return null;
}

function AuthInitializer() {
  const store = useStore();

  useEffect(() => {
    // Run in background, don't block rendering
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
    );
}

