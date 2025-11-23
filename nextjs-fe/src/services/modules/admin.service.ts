/**
 * Admin Service
 * Service for Admin Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ADMIN_MST_LIST, ADMIN_MST_STORE, ADMIN_MST_UPDATE, ADMIN_MST_DELETE } from '@/constants/apiUrl';
import type { AdminMst } from '@/types/models';

export const adminService = createCrudService<AdminMst>({
  baseUrl: '/admin/admin-mst',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});

// Additional admin-specific methods can be added here
export const adminServiceExtended = {
  ...adminService,

  /**
   * Assign roles to admin
   */
  async assignRoles(adminId: number, roleIds: number[]): Promise<void> {
    // Implementation will depend on API structure
    console.log('Assign roles:', adminId, roleIds);
  },

  /**
   * Assign departments to admin
   */
  async assignDepartments(adminId: number, departmentIds: number[]): Promise<void> {
    // Implementation will depend on API structure
    console.log('Assign departments:', adminId, departmentIds);
  },

  /**
   * Change admin password
   */
  async changePassword(adminId: number, newPassword: string): Promise<void> {
    // Implementation will depend on API structure
    console.log('Change password for admin:', adminId);
  },
};
