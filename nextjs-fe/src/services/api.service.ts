import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { ApiMst, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export interface ApiListParams extends ListQueryParams {
  id?: number;
  name?: string;
  status?: number;
  is_active?: boolean;
}

export const apiService = {
  async list(params: ApiListParams = {}) {
    return apiClient.get<PaginatedResponse<ApiMst>>(ENDPOINTS.MASTER.API, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<ApiMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.API, data);
  },

  async update(id: number, data: Partial<ApiMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.API}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.API, { ids });
  },
};
