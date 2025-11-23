/**
 * Skill Service
 */

import { createCrudService } from '@/services/crud-service';
import type { SkillMgmt } from '@/types/models';

export const skillService = createCrudService<SkillMgmt>({
  baseUrl: '/admin/skill-mgmt',
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});
