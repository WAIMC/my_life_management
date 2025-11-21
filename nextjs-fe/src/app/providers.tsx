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
import { useDispatch, useSelector } from 'react-redux';
import type { RootState, AppStore } from '@/redux/store';

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

          // Redirect to login
          if (typeof window !== 'undefined') {
            window.location.href = '/login';
          }
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
  const [initialized, setInitialized] = useState(false);
  const store = useStore();

  useEffect(() => {
    initializeAuth(store as AppStore).finally(() => {
      setInitialized(true);
    });
  }, [store]);

  if (!initialized) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="mb-4 h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-slate-600 dark:border-slate-700 dark:border-t-slate-300 mx-auto"></div>
          <p className="text-sm text-slate-600 dark:text-slate-400">Initializing...</p>
        </div>
      </div>
    );
  }

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

