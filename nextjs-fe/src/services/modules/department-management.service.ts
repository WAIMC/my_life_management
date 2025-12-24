/**
 * Department Management Junction Service
 * Service for Department-Management Many-to-Many relationship
 */

import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ListQueryParams } from '@/lib/types/api';

export const departmentManagementService = {
  /**
   * Get list of department-management relationships
   */
  async list(params?: ListQueryParams): Promise<any> {
    const url = `${ENDPOINTS.JUNCTION.DEPARTMENT_MANAGEMENT}/list`;
    const response = await apiClient.get(url, params);
    return response.data;
  },

  /**
   * Update department-management relationships
   */
  async update(departmentId: number, managementIds: number[]): Promise<void> {
    const url = `${ENDPOINTS.JUNCTION.DEPARTMENT_MANAGEMENT}/update`;
    await apiClient.put(url, { department_id: departmentId, management_ids: managementIds });
  },
};
