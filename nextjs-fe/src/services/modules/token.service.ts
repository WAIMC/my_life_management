/**
 * Token Service
 * Service for Token Master CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { TokenMst } from '@/types/models';

export const tokenService = createCrudService<TokenMst>({
  baseUrl: ENDPOINTS.MASTER.TOKEN,
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
