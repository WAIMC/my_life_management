import { describe, it, expect, vi } from 'vitest';
import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { useApiData } from '../useApiData';
import { apiClient } from '@/lib/api-client';

// Mock API client
vi.mock('@/lib/api-client', () => ({
  apiClient: {
    get: vi.fn(),
  },
}));

describe('useApiData', () => {
  const createWrapper = () => {
    const queryClient = new QueryClient({
      defaultOptions: {
        queries: { retry: false },
      },
    });
    return ({ children }: { children: React.ReactNode }) => (
      <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
    );
  };

  it('should fetch data successfully', async () => {
    const mockData = {
      data: {
        data: [{ id: 1, name: 'Test' }],
        current_page: 1,
        last_page: 1,
        total: 1,
        per_page: 20,
        from: 1,
        to: 1,
      },
    };

    vi.mocked(apiClient.get).mockResolvedValueOnce(mockData);

    const { result } = renderHook(
      () => useApiData('/test-endpoint', { page: 1 }),
      { wrapper: createWrapper() }
    );

    expect(result.current.loading).toBe(true);

    await waitFor(() => {
      expect(result.current.loading).toBe(false);
    });

    expect(result.current.data).toEqual([{ id: 1, name: 'Test' }]);
    expect(result.current.pagination.total).toBe(1);
  });

  it('should handle errors', async () => {
    const mockError = new Error('API Error');
    vi.mocked(apiClient.get).mockRejectedValueOnce(mockError);

    const { result } = renderHook(
      () => useApiData('/test-endpoint', { page: 1 }),
      { wrapper: createWrapper() }
    );

    await waitFor(() => {
      expect(result.current.loading).toBe(false);
    });

    expect(result.current.error).toBeTruthy();
  });

  it('should not fetch when disabled', () => {
    const { result } = renderHook(
      () => useApiData('/test-endpoint', { enabled: false }),
      { wrapper: createWrapper() }
    );

    expect(apiClient.get).not.toHaveBeenCalled();
    expect(result.current.data).toEqual([]);
  });
});
