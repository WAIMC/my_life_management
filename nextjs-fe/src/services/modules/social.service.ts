/**
 * Social Service
 */

import { createCrudService } from '@/services/crud-service';
import type { SocialMgmt } from '@/types/models';

export const socialService = createCrudService<SocialMgmt>({
  baseUrl: '/admin/social-mgmt',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
