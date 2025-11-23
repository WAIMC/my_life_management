/**
 * Feature Service
 */

import { createCrudService } from '@/services/crud-service';
import type { FeatureMst } from '@/types/models';

export const featureService = createCrudService<FeatureMst>({
  baseUrl: '/admin/feature-mst',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
