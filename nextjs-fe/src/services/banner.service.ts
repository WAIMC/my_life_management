import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { BannerMgmt, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const bannerService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<BannerMgmt>>(ENDPOINTS.MANAGEMENT.BANNER, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },
  async create(data: Omit<BannerMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.BANNER, data);
  },
  async update(id: number, data: Partial<BannerMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.BANNER}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.BANNER, { ids });
  },
};
