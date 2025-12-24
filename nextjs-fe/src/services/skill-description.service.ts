import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { SkillDescriptionMgmt, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const skillDescriptionService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<SkillDescriptionMgmt>>(`${ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION}/list`, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data.data[0] || null;
  },
  async create(data: Omit<SkillDescriptionMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(`${ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION}/store`, data);
  },
  async update(id: number, data: Partial<SkillDescriptionMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION}/update/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(`${ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION}/delete`, { ids });
  },
};
