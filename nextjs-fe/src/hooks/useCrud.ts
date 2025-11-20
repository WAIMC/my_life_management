'use client';

import { useState, useCallback } from 'react';
import { apiClient } from '@/lib/api-client';
import { notification } from '@/lib/notification';

interface UseCrudReturn<T> {
  create: (data: Partial<T>) => Promise<number>;
  update: (id: number, data: Partial<T>) => Promise<number>;
  remove: (ids: number[]) => Promise<void>;
  loading: boolean;
  error: Error | null;
}

/**
 * Generic CRUD operations hook
 * Handles create, update, delete with loading states and notifications
 */
export function useCrud<T>(endpoint: string): UseCrudReturn<T> {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);

  /**
   * Create new record
   */
  const create = useCallback(
    async (data: Partial<T>): Promise<number> => {
      try {
        setLoading(true);
        setError(null);

        const response = await apiClient.post<number>(endpoint, data);
        notification.success('Created successfully');

        return response.data;
      } catch (err: any) {
        setError(err);
        const message = err.response?.data?.message || 'Failed to create';
        notification.error(message);
        throw err;
      } finally {
        setLoading(false);
      }
    },
    [endpoint]
  );

  /**
   * Update existing record
   */
  const update = useCallback(
    async (id: number, data: Partial<T>): Promise<number> => {
      try {
        setLoading(true);
        setError(null);

        const response = await apiClient.put<number>(`${endpoint}/${id}`, {
          id,
          ...data,
        });
        notification.success('Updated successfully');

        return response.data;
      } catch (err: any) {
        setError(err);
        const message = err.response?.data?.message || 'Failed to update';
        notification.error(message);
        throw err;
      } finally {
        setLoading(false);
      }
    },
    [endpoint]
  );

  /**
   * Delete record(s)
   */
  const remove = useCallback(
    async (ids: number[]): Promise<void> => {
      try {
        setLoading(true);
        setError(null);

        await apiClient.delete(endpoint, { ids });
        notification.success(
          `Deleted ${ids.length} item${ids.length > 1 ? 's' : ''} successfully`
        );
      } catch (err: any) {
        setError(err);
        const message = err.response?.data?.message || 'Failed to delete';
        notification.error(message);
        throw err;
      } finally {
        setLoading(false);
      }
    },
    [endpoint]
  );

  return {
    create,
    update,
    remove,
    loading,
    error,
  };
}
