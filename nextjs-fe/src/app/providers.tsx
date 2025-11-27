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
          {/* DISABLED: Authentication components */}
          {/* <BroadcastListener /> */}
          {/* <AuthInitializer /> */}
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

