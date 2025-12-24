/**
 * Policy Department Service
 * Service for Policy Department Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { PolicyDepartmentMst } from '@/types/models';

export const policyDepartmentService = createCrudService<PolicyDepartmentMst>({
  baseUrl: ENDPOINTS.MASTER.POLICY_DEPARTMENT,
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
