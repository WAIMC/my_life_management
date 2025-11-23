/**
 * Language Service
 */

import { createCrudService } from '@/services/crud-service';
import type { LanguageMst } from '@/types/models';

export const languageService = createCrudService<LanguageMst>({
  baseUrl: '/admin/language-mst',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
