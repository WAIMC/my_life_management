/**
 * Feature Service
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { FeatureMst } from '@/types/models';

export const featureService = createCrudService<FeatureMst>({
  baseUrl: ENDPOINTS.MASTER.FEATURE,
  endpoints: {
    delete: '/delete',
  },
});
