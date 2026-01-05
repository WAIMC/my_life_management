/**
 * API Service
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ApiMst } from '@/types/models';

export const apiService = createCrudService<ApiMst>({
  baseUrl: ENDPOINTS.MASTER.API,
  endpoints: {
    delete: '/delete',
  },
});
