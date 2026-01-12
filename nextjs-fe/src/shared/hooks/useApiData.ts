'use client';

import { useQuery } from '@tanstack/react-query';
import { apiClient } from '@/shared/api/client';
import type {
  PaginatedResponse,
  UseApiDataOptions,
  UseApiDataReturn,
  FilterValue,
} from '@/shared/types/api';
import { PAGINATION, SORT_ORDER } from '@/shared/config/constant';

/**
 * Generic hook for fetching paginated data from API using TanStack Query
 * Supports pagination, filtering, sorting, and date range
 * 
 * Benefits over old implementation:
 * - Automatic caching and background refetching
 * - Request deduplication
 * - Better loading states (initial load vs refetch)
 * - Automatic retry on failure
 */
export function useApiData<T>(
  endpoint: string,
  options: UseApiDataOptions = {}
): UseApiDataReturn<T> {
  const {
    page = PAGINATION.DEFAULT_PAGE,
    per_page = PAGINATION.DEFAULT_PER_PAGE,
    filters = {},
    sort_by,
    sort_order = SORT_ORDER.ASC,
    from_date,
    to_date,
    enabled = true,
  } = options;

  // Build query key for caching
  const queryKey = [
    endpoint,
    {
      page,
      per_page,
      filters,
      sort_by,
      sort_order,
      from_date,
      to_date,
    },
  ];

  // Fetch function
  const fetchData = async (): Promise<PaginatedResponse<T>> => {
    const params: Record<string, FilterValue> = {
      page,
      per_page,
      ...filters,
    };

    if (sort_by) params.sort_by = sort_by;
    if (sort_order) params.sort_order = sort_order;
    if (from_date) params.from_date = from_date;
    if (to_date) params.to_date = to_date;

    const response = await apiClient.get<PaginatedResponse<T>>(
      `${endpoint}/list`,
      params
    );

    return response.data;
  };

  // Use TanStack Query
  const query = useQuery({
    queryKey,
    queryFn: fetchData,
    enabled,
    // Keep previous data while fetching new page
    placeholderData: (previousData) => previousData,
  });

  // Extract pagination info
  const paginationData = query.data || {
    data: [],
    current_page: PAGINATION.DEFAULT_PAGE,
    last_page: PAGINATION.DEFAULT_TOTAL_PAGES,
    total: PAGINATION.DEFAULT_TOTAL,
    per_page: PAGINATION.DEFAULT_PER_PAGE,
    from: PAGINATION.DEFAULT_FROM,
    to: PAGINATION.DEFAULT_TO,
  };

  return {
    data: paginationData.data || [],
    loading: query.isLoading,
    error: query.error as Error | null,
    pagination: {
      currentPage: paginationData.current_page,
      lastPage: paginationData.last_page,
      total: paginationData.total,
      perPage: paginationData.per_page,
      from: paginationData.from,
      to: paginationData.to,
    },
    refetch: () => {
      query.refetch();
    },
    isRefetching: query.isRefetching,
  };
}
