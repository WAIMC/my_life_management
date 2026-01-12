import { ENDPOINTS } from '@/shared/api';
import type { SkillMgmt } from '@/shared/types/api';
import { createCrudService } from '../factories';

export const skillService = createCrudService<SkillMgmt>({
  endpoint: ENDPOINTS.MANAGEMENT.SKILL,
});
