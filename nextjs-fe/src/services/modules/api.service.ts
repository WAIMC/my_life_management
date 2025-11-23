/**
 * API Service
 */

import { createCrudService } from '@/services/crud-service';
import type { ApiMst } from '@/types/models';

export const apiService = createCrudService<ApiMst>({
  baseUrl: '/admin/api-mst',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
