/**
 * API Role Junction Service
 * Service for API-Role Many-to-Many relationship
 */

import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ListQueryParams } from '@/lib/types/api';

export const apiRoleService = {
  /**
   * Get list of api-role relationships
   */
  async list(params?: ListQueryParams): Promise<any> {
    const url = `${ENDPOINTS.JUNCTION.API_ROLE}/list`;
    const response = await apiClient.get(url, params);
    return response.data;
  },

  /**
   * Update api-role relationships for an API
   */
  async update(apiId: number, roleIds: number[]): Promise<void> {
    const url = `${ENDPOINTS.JUNCTION.API_ROLE}/update`;
    await apiClient.put(url, { api_id: apiId, role_ids: roleIds });
  },
};
