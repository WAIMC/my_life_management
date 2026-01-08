/**
 * useHistory Hook
 * Hook for fetching and managing history data
 */

import { useState, useCallback } from 'react';
import { apiClient } from '@/shared/api/client';
import type { PaginatedResponse } from '@/shared/types/api';
import type { BaseHistory, HistoryFilterOptions, HistoryDiff } from '@/shared/types/models/history';

interface UseHistoryOptions {
  baseUrl: string;
  recordId: number;
}

interface UseHistoryReturn<T extends BaseHistory> {
  history: T[];
  isLoading: boolean;
  error: Error | null;
  pagination: {
    page: number;
    perPage: number;
    total: number;
  };
  fetchHistory: (filters?: HistoryFilterOptions) => Promise<void>;
  compareVersions: (oldVersion: T, newVersion: T) => HistoryDiff[];
  restoreVersion: (historyId: number) => Promise<void>;
}

export function useHistory<T extends BaseHistory = BaseHistory>({
  baseUrl,
  recordId,
}: UseHistoryOptions): UseHistoryReturn<T> {
  const [history, setHistory] = useState<T[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);
  const [pagination, setPagination] = useState({
    page: 1,
    perPage: 20,
    total: 0,
  });

  const fetchHistory = useCallback(
    async (filters?: HistoryFilterOptions) => {
      setIsLoading(true);
      setError(null);

      try {
        const params = {
          record_id: recordId,
          page: filters?.page || pagination.page,
          per_page: filters?.per_page || pagination.perPage,
          action: filters?.action,
          changed_by: filters?.changed_by,
          from_date: filters?.from_date,
          to_date: filters?.to_date,
        };

        const response = await apiClient.get<PaginatedResponse<T>>(
          `${baseUrl}/list`,
          params
        );

        setHistory(response.data.data);
        setPagination({
          page: response.data.current_page,
          perPage: response.data.per_page,
          total: response.data.total,
        });
      } catch (err) {
        setError(err instanceof Error ? err : new Error('Failed to fetch history'));
      } finally {
        setIsLoading(false);
      }
    },
    [baseUrl, recordId, pagination.page, pagination.perPage]
  );

  const compareVersions = useCallback((oldVersion: T, newVersion: T): HistoryDiff[] => {
    const diffs: HistoryDiff[] = [];
    const oldValues = oldVersion.old_values || {};
    const newValues = newVersion.new_values || {};

    // Get all unique keys from both versions
    const allKeys = new Set([
      ...Object.keys(oldValues),
      ...Object.keys(newValues),
    ]);

    allKeys.forEach((key) => {
      const oldValue = oldValues[key];
      const newValue = newValues[key];

      // Only add to diffs if values are different
      if (JSON.stringify(oldValue) !== JSON.stringify(newValue)) {
        diffs.push({
          field: key,
          oldValue,
          newValue,
          label: key.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase()),
        });
      }
    });

    return diffs;
  }, []);

  const restoreVersion = useCallback(
    async (historyId: number) => {
      try {
        await apiClient.post(`${baseUrl}/restore/${historyId}`, {});
        // Refresh history after restore
        await fetchHistory();
      } catch (err) {
        throw err instanceof Error ? err : new Error('Failed to restore version');
      }
    },
    [baseUrl, fetchHistory]
  );

  return {
    history,
    isLoading,
    error,
    pagination,
    fetchHistory,
    compareVersions,
    restoreVersion,
  };
}
