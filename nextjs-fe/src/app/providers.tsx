'use client';

import { Provider } from 'react-redux';
import { QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import { Toaster } from 'react-hot-toast';
import { makeStore } from '../redux/store';
import { useEffect, useMemo } from 'react';
import { useRouter } from 'next/navigation';
import { setNavigateFunction, clearNavigateFunction } from '@/lib/navigation';
import { QueryClient } from '@tanstack/react-query';
import { ThemeProvider } from 'next-themes';

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

import { AuthProvider } from '@/providers/auth-provider';

export function Providers({ children }: { children: React.ReactNode }) {
  const store = useMemo(() => {
    return makeStore();
  }, []);

  return (
    <QueryClientProvider client={queryClient}>
      <Provider store={store}>
        <AuthProvider>
          <ThemeProvider
            attribute="class"
            defaultTheme="system"
            enableSystem
            disableTransitionOnChange={false}
          >
            <NavigationProvider />
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
        </AuthProvider>
      </Provider>
      {/* React Query Devtools - only in development */}
      {process.env.NODE_ENV === 'development' && (
        <ReactQueryDevtools initialIsOpen={false} />
      )}
    </QueryClientProvider>
  );
}

