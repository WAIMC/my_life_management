/**
 * Role Service
 * Service for Role Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { RoleMst } from '@/types/models';

export const roleService = createCrudService<RoleMst>({
  baseUrl: ENDPOINTS.MASTER.ROLE,
  endpoints: {
    delete: '/delete',
  },
});

// Additional role-specific methods
export const roleServiceExtended = {
  ...roleService,

  /**
   * Assign APIs to role
   */
  async assignApis(roleId: number, apiIds: number[]): Promise<void> {
    // Implementation will depend on API structure
  },

  /**
   * Get role permissions
   */
  async getPermissions(roleId: number): Promise<any[]> {
    // Implementation will depend on API structure
    return [];
  },
};
