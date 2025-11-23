/**
 * Translation Service
 */

import { createCrudService } from '@/services/crud-service';
import type { TranslationMst } from '@/types/models';

export const translationService = createCrudService<TranslationMst>({
  baseUrl: '/admin/translation-mst',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
