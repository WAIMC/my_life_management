/**
 * Banner Service
 */

import { createCrudService } from '@/services/crud-service';
import type { BannerMgmt } from '@/types/models';

export const bannerService = createCrudService<BannerMgmt>({
  baseUrl: '/admin/banner-mgmt',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
