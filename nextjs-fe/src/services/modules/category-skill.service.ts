/**
 * Category Skill Junction Service
 * Service for Category-Skill Many-to-Many relationship
 */

import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ListQueryParams } from '@/lib/types/api';

export const categorySkillService = {
  /**
   * Get list of category-skill relationships
   */
  async list(params?: ListQueryParams): Promise<any> {
    const url = `${ENDPOINTS.JUNCTION.CATEGORY_SKILL}/list`;
    const response = await apiClient.get(url, params);
    return response.data;
  },

  /**
   * Update category-skill relationships
   */
  async update(categoryId: number, skillIds: number[]): Promise<void> {
    const url = `${ENDPOINTS.JUNCTION.CATEGORY_SKILL}/update`;
    await apiClient.put(url, { category_id: categoryId, skill_ids: skillIds });
  },
};
