import { apiClient } from '@/shared/api/client';
import { ENDPOINTS } from '@/shared/api';
import type { CategoryMgmt, PaginatedResponse, ListQueryParams } from '@/shared/types/api';

export const categoryService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<CategoryMgmt>>(ENDPOINTS.MANAGEMENT.CATEGORY, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data.data[0] || null;
  },
  async create(data: Omit<CategoryMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.CATEGORY, data);
  },
  async update(id: number, data: Partial<CategoryMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.CATEGORY}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.CATEGORY, { ids });
  },
};
