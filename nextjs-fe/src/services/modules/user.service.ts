/**
 * User Service
 */

import { createCrudService } from '@/services/crud-service';
import type { UserMgmt } from '@/types/models';

export const userService = createCrudService<UserMgmt>({
  baseUrl: '/admin/user-mgmt',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
