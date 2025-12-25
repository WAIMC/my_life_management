/**
 * Department Service
 * Service for Department Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { DepartmentMst } from '@/types/models';

export const departmentService = createCrudService<DepartmentMst>({
  baseUrl: ENDPOINTS.MASTER.DEPARTMENT,
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});

// Additional department-specific methods
export const departmentServiceExtended = {
  ...departmentService,

  /**
   * Get department tree (hierarchical structure)
   */
  async getTree(): Promise<DepartmentMst[]> {
    // Implementation will depend on API structure
    return [];
  },

  /**
   * Assign admins to department
   */
  async assignAdmins(departmentId: number, adminIds: number[]): Promise<void> {
    // Implementation will depend on API structure
  },
};
