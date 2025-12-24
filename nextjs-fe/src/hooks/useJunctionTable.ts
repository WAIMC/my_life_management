'use client';

import { useState, useCallback, useEffect } from 'react';
import { apiClient } from '@/lib/api-client';
import { notification } from '@/lib/notification';
import type { UpdateJunctionRequest } from '@/lib/types/api';

interface UseJunctionTableReturn<T> {
  allItems: any[];
  assignedIds: number[];
  selectedIds: number[];
  loading: boolean;
  saving: boolean;
  setSelectedIds: (ids: number[]) => void;
  toggleSelection: (id: number) => void;
  save: () => Promise<void>;
  refetch: () => Promise<void>;
}

/**
 * Hook for managing junction table relationships
 * Handles fetching, selecting, and updating many-to-many relationships
 */
export function useJunctionTable<T>(
  junctionEndpoint: string,
  allItemsEndpoint: string,
  parentIdKey: string,
  childIdKey: string,
  parentId: number
): UseJunctionTableReturn<T> {
  const [allItems, setAllItems] = useState<any[]>([]);
  const [assignedIds, setAssignedIds] = useState<number[]>([]);
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  /**
   * Fetch all available items and assigned relationships
   */
  const fetchData = useCallback(async () => {
    try {
      setLoading(true);

      // Fetch all available items (e.g., all roles)
      const allItemsResponse = await apiClient.get(allItemsEndpoint, {
        per_page: 100,
      });
      const data = allItemsResponse.data as any;
      setAllItems(data.data || data);

      // Fetch assigned relationships
      const assignedResponse = await apiClient.get(junctionEndpoint, {
        [parentIdKey]: parentId,
        per_page: 100,
      });

      const assignedData = assignedResponse.data as any;
      const assigned = assignedData.data || assignedData;
      const assignedItemIds = assigned.map((item: any) => item[childIdKey]);

      setAssignedIds(assignedItemIds);
      setSelectedIds(assignedItemIds);
    } catch (error: any) {
      const message = error.response?.data?.message || 'Failed to load data';
      notification.error(message);
    } finally {
      setLoading(false);
    }
  }, [junctionEndpoint, allItemsEndpoint, parentIdKey, childIdKey, parentId]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  /**
   * Toggle selection of an item
   */
  const toggleSelection = useCallback((id: number) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((i) => i !== id) : [...prev, id]
    );
  }, []);

  /**
   * Save changes (insert new and delete removed relationships)
   */
  const save = useCallback(async () => {
    try {
      setSaving(true);

      // Calculate what to delete and insert
      const toDelete = assignedIds
        .filter((id) => !selectedIds.includes(id))
        .map((id) => ({
          [parentIdKey]: parentId,
          [childIdKey]: id,
        }));

      const toInsert = selectedIds
        .filter((id) => !assignedIds.includes(id))
        .map((id) => ({
          [parentIdKey]: parentId,
          [childIdKey]: id,
        }));

      if (toDelete.length === 0 && toInsert.length === 0) {
        notification.success('No changes to save');
        return;
      }

      // Update junction table
      const updateData: any = {
        [parentIdKey]: parentId,
      };

      if (toDelete.length > 0) updateData.delete = toDelete;
      if (toInsert.length > 0) updateData.insert = toInsert;

      await apiClient.put(junctionEndpoint, updateData);

      setAssignedIds(selectedIds);
      notification.success('Relationships updated successfully');
    } catch (error: any) {
      const message = error.response?.data?.message || 'Failed to update';
      notification.error(message);
      throw error;
    } finally {
      setSaving(false);
    }
  }, [
    junctionEndpoint,
    parentIdKey,
    childIdKey,
    parentId,
    assignedIds,
    selectedIds,
  ]);

  return {
    allItems,
    assignedIds,
    selectedIds,
    loading,
    saving,
    setSelectedIds,
    toggleSelection,
    save,
    refetch: fetchData,
  };
}
