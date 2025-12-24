import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { SkillMgmt, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const skillService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<SkillMgmt>>(ENDPOINTS.MANAGEMENT.SKILL, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data.data[0] || null;
  },
  async create(data: Omit<SkillMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.SKILL, data);
  },
  async update(id: number, data: Partial<SkillMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.SKILL}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.SKILL, { ids });
  },
};
