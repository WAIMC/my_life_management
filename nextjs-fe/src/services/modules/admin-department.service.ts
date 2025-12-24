/**
 * Admin Department Junction Service
 * Service for Admin-Department Many-to-Many relationship
 */

import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ListQueryParams } from '@/lib/types/api';

export const adminDepartmentService = {
  /**
   * Get list of admin-department relationships
   */
  async list(params?: ListQueryParams): Promise<any> {
    const url = `${ENDPOINTS.JUNCTION.ADMIN_DEPARTMENT}/list`;
    const response = await apiClient.get(url, params);
    return response.data;
  },

  /**
   * Update admin-department relationships for an admin
   */
  async update(adminId: number, departmentIds: number[]): Promise<void> {
    const url = `${ENDPOINTS.JUNCTION.ADMIN_DEPARTMENT}/update`;
    await apiClient.put(url, { admin_id: adminId, department_ids: departmentIds });
  },
};
