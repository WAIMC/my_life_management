import { ENDPOINTS } from '@/shared/api';
import type { SkillDescriptionMgmt } from '@/shared/types/api';
import { createCrudService } from '../factories';

export const skillDescriptionService = createCrudService<SkillDescriptionMgmt>({
  endpoint: ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION,
  useSuffix: true,
});
