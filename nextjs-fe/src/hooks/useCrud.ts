'use client';

import { useMutation, useQueryClient } from '@tanstack/react-query';
import { apiClient } from '@/lib/api-client';
import { notification } from '@/lib/notification';
import { AxiosError } from 'axios';

interface UseCrudReturn<T> {
  create: (data: Partial<T>) => Promise<number>;
  update: (id: number, data: Partial<T>) => Promise<number>;
  remove: (ids: number[]) => Promise<void>;
  loading: boolean;
  error: Error | AxiosError | null;
}

interface UseCrudOptions {
  /**
   * Query keys to invalidate after successful mutation
   * Example: ['users'] will invalidate all user-related queries
   */
  invalidateKeys?: string[];
  
  /**
   * Custom success messages
   */
  messages?: {
    create?: string;
    update?: string;
    delete?: string;
  };
}

/**
 * Generic CRUD operations hook using TanStack Query mutations
 * Handles create, update, delete with loading states and notifications
 * 
 * Benefits over old implementation:
 * - Automatic query invalidation after mutations
 * - Better error handling
 * - Optimistic updates support (can be added)
 * - Mutation state tracking
 */
export function useCrud<T>(
  endpoint: string,
  options: UseCrudOptions = {}
): UseCrudReturn<T> {
  const queryClient = useQueryClient();
  const { invalidateKeys = [endpoint], messages = {} } = options;

  /**
   * Create mutation
   */
  const createMutation = useMutation({
    mutationFn: async (data: Partial<T>): Promise<number> => {
      const response = await apiClient.post<number>(`${endpoint}/store`, data);
      return response.data;
    },
    onSuccess: () => {
      notification.success(messages.create || 'Created successfully');
      // Invalidate queries to refetch data
      invalidateKeys.forEach((key) => {
        queryClient.invalidateQueries({ queryKey: [key] });
      });
    },
    onError: (err: any) => {
      // If it's a validation error (422), we don't show a generic toast, 
      // because the form will handle showing specific field errors.
      if (err.response?.status !== 422) {
         const message = err.response?.data?.message || 'Failed to create';
         notification.error(message);
      }
    },
  });

  /**
   * Update mutation
   */
  const updateMutation = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: Partial<T> }): Promise<number> => {
      const response = await apiClient.put<number>(`${endpoint}/update/${id}`, {
        id,
        ...data,
      });
      return response.data;
    },
    onSuccess: () => {
      notification.success(messages.update || 'Updated successfully');
      // Invalidate queries to refetch data
      invalidateKeys.forEach((key) => {
        queryClient.invalidateQueries({ queryKey: [key] });
      });
    },
    onError: (err: any) => {
      // If it's a validation error (422), we don't show a generic toast
      if (err.response?.status !== 422) {
        const message = err.response?.data?.message || 'Failed to update';
        notification.error(message);
      }
    },
  });

  /**
   * Delete mutation
   */
  const deleteMutation = useMutation({
    mutationFn: async (ids: number[]) => {
      /*
       * Refactored to support Batch Delete via POST Payload
       * Sends { ids: number[] } to the {endpoint}/delete endpoint
       */
      await apiClient.post(`${endpoint}/delete`, { ids });
    },
    onSuccess: (_, ids) => {
      notification.success(
        messages.delete || `Deleted ${ids.length} item${ids.length > 1 ? 's' : ''} successfully`
      );
      // Invalidate queries to refetch data
      invalidateKeys.forEach((key) => {
        queryClient.invalidateQueries({ queryKey: [key] });
      });
    },
    onError: (err: any) => {
      const message = err.response?.data?.message || 'Failed to delete';
      notification.error(message);
    },
  });

  // Wrapper functions to maintain API compatibility
  const create = async (data: Partial<T>): Promise<number> => {
    return createMutation.mutateAsync(data);
  };

  const update = async (id: number, data: Partial<T>): Promise<number> => {
    return updateMutation.mutateAsync({ id, data });
  };

  const remove = async (ids: number[]): Promise<void> => {
    return deleteMutation.mutateAsync(ids);
  };

  // Combine loading states
  const loading = createMutation.isPending || updateMutation.isPending || deleteMutation.isPending;
  
  // Combine errors (return the first error if any)
  const error = (createMutation.error || updateMutation.error || deleteMutation.error) as Error | AxiosError | null;

  return {
    create,
    update,
    remove,
    loading,
    error,
  };
}
