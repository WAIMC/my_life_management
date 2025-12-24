/**
 * Admin Role Junction Service
 * Service for Admin-Role Many-to-Many relationship
 */

import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ApiResponse, ListQueryParams } from '@/lib/types/api';

export const adminRoleService = {
  /**
   * Get list of admin-role relationships
   */
  async list(params?: ListQueryParams): Promise<any> {
    const url = `${ENDPOINTS.JUNCTION.ADMIN_ROLE}/list`;
    const response = await apiClient.get(url, params);
    return response.data;
  },

  /**
   * Update admin-role relationships for an admin
   */
  async update(adminId: number, roleIds: number[]): Promise<void> {
    const url = `${ENDPOINTS.JUNCTION.ADMIN_ROLE}/update`;
    await apiClient.put(url, { admin_id: adminId, role_ids: roleIds });
  },
};
