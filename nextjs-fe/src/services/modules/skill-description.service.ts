/**
 * Skill Description Service
 * Service for Skill Description Management CRUD operations
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { SkillDescriptionMgmt } from '@/types/models';

export const skillDescriptionService = createCrudService<SkillDescriptionMgmt>({
  baseUrl: ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION,
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
