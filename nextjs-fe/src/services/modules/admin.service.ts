/**
 * Admin Service
 * Service for Admin Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { AdminMst } from '@/types/models';

export const adminService = createCrudService<AdminMst>({
  baseUrl: ENDPOINTS.MASTER.ADMIN,
  endpoints: {
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
  },

  /**
   * Assign departments to admin
   */
  async assignDepartments(adminId: number, departmentIds: number[]): Promise<void> {
    // Implementation will depend on API structure
  },

  /**
   * Change admin password
   */
  async changePassword(adminId: number, newPassword: string): Promise<void> {
    // Implementation will depend on API structure
  },
};
