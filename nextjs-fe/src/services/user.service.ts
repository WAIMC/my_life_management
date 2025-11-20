import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { UserMgmt, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const userService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<UserMgmt>>(ENDPOINTS.MANAGEMENT.USER, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },
  async create(data: Omit<UserMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.USER, data);
  },
  async update(id: number, data: Partial<UserMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.USER}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.USER, { ids });
  },
};
