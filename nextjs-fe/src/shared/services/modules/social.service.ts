import { apiClient } from '@/shared/api/client';
import { ENDPOINTS } from '@/shared/api';
import type { SocialMgmt, PaginatedResponse, ListQueryParams } from '@/shared/types/api';

export const socialService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<SocialMgmt>>(ENDPOINTS.MANAGEMENT.SOCIAL, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data.data[0] || null;
  },
  async create(data: Omit<SocialMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.SOCIAL, data);
  },
  async update(id: number, data: Partial<SocialMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.SOCIAL}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.SOCIAL, { ids });
  },
};
