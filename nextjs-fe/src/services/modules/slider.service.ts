/**
 * Slider Service
 */

import { createCrudService } from '@/services/crud-service';
import type { SliderMgmt } from '@/types/models';

export const sliderService = createCrudService<SliderMgmt>({
  baseUrl: '/admin/slider-mgmt',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
