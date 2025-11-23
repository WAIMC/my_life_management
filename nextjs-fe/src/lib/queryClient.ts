import { QueryClient } from '@tanstack/react-query';

/**
 * TanStack Query Client Configuration
 * 
 * Global configuration for React Query including:
 * - Default query options (staleTime, cacheTime, retry)
 * - Default mutation options
 * - Error handling defaults
 */

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      // Data is considered fresh for 30 seconds
      staleTime: 30 * 1000,
      
      // Cache data for 5 minutes
      gcTime: 5 * 60 * 1000,
      
      // Retry failed requests 1 time
      retry: 1,
      
      // Retry delay with exponential backoff
      retryDelay: (attemptIndex: number) => Math.min(1000 * 2 ** attemptIndex, 30000),
      
      // Refetch on window focus for important data
      refetchOnWindowFocus: true,
      
      // Don't refetch on mount if data is fresh
      refetchOnMount: false,
      
      // Refetch on reconnect
      refetchOnReconnect: true,
    },
    mutations: {
      // Retry mutations once on failure
      retry: 1,
      
      // Retry delay for mutations
      retryDelay: 1000,
    },
  },
});

/**
 * Helper function to invalidate queries by key
 * Usage: invalidateQueries(['users']) will invalidate all user-related queries
 */
export const invalidateQueries = (queryKey: string[]) => {
  return queryClient.invalidateQueries({ queryKey });
};

/**
 * Helper function to reset all queries
 * Useful for logout scenarios
 */
export const resetQueries = () => {
  return queryClient.clear();
};

/**
 * Helper to prefetch data
 * Useful for optimistic navigation
 */
export const prefetchQuery = async <T>(
  queryKey: string[],
  queryFn: () => Promise<T>
) => {
  await queryClient.prefetchQuery({
    queryKey,
    queryFn,
  });
};
