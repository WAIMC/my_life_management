'use client';

import { useState, useEffect, useCallback } from 'react';
import { apiClient } from '@/lib/api-client';
import type { PaginatedResponse, ListQueryParams } from '@/lib/types/api';

interface UseApiDataOptions extends ListQueryParams {
  filters?: Record<string, any>;
  enabled?: boolean; // If false, don't fetch automatically
}

interface UseApiDataReturn<T> {
  data: T[];
  loading: boolean;
  error: Error | null;
  pagination: {
    currentPage: number;
    lastPage: number;
    total: number;
    perPage: number;
    from: number;
    to: number;
  };
  refetch: () => Promise<void>;
}

/**
 * Generic hook for fetching paginated data from API
 * Supports pagination, filtering, sorting, and date range
 */
export function useApiData<T>(
  endpoint: string,
  options: UseApiDataOptions = {}
): UseApiDataReturn<T> {
  const {
    page = 1,
    per_page = 15,
    filters = {},
    sort_by,
    sort_order = 'asc',
    from_date,
    to_date,
    enabled = true,
  } = options;

  const [data, setData] = useState<T[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);
  const [pagination, setPagination] = useState({
    currentPage: 1,
    lastPage: 1,
    total: 0,
    perPage: 15,
    from: 0,
    to: 0,
  });

  const fetchData = useCallback(async () => {
    if (!enabled) return;

    try {
      setLoading(true);
      setError(null);

      const params: Record<string, any> = {
        page,
        per_page,
        ...filters,
      };

      if (sort_by) params.sort_by = sort_by;
      if (sort_order) params.sort_order = sort_order;
      if (from_date) params.from_date = from_date;
      if (to_date) params.to_date = to_date;

      const response = await apiClient.get<PaginatedResponse<T>>(
        endpoint,
        params
      );

      setData(response.data.data);
      setPagination({
        currentPage: response.data.current_page,
        lastPage: response.data.last_page,
        total: response.data.total,
        perPage: response.data.per_page,
        from: response.data.from,
        to: response.data.to,
      });
    } catch (err) {
      setError(err as Error);
      console.error('Failed to fetch data:', err);
    } finally {
      setLoading(false);
    }
  }, [endpoint, page, per_page, filters, sort_by, sort_order, from_date, to_date, enabled]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  return {
    data,
    loading,
    error,
    pagination,
    refetch: fetchData,
  };
}
