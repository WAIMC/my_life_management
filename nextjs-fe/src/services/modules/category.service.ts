/**
 * Category Service
 */

import { createCrudService } from '@/services/crud-service';
import type { CategoryMgmt } from '@/types/models';

export const categoryService = createCrudService<CategoryMgmt>({
  baseUrl: '/admin/category-mgmt',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
