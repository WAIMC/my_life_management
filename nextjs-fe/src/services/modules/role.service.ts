/**
 * Role Service
 * Service for Role Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ROLE_MST_LIST } from '@/constants/apiUrl';
import type { RoleMst } from '@/types/models';

export const roleService = createCrudService<RoleMst>({
  baseUrl: '/admin/role-mst',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
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
