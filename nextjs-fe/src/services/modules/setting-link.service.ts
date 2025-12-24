/**
 * Setting Link Service
 * Service for Setting Link Management CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { SettingLinkMgmt } from '@/types/models';

export const settingLinkService = createCrudService<SettingLinkMgmt>({
  baseUrl: ENDPOINTS.MANAGEMENT.SETTING_LINK,
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
