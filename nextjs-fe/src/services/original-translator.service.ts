import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { OriginalTranslatorMst, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const originalTranslatorService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<OriginalTranslatorMst>>(ENDPOINTS.MASTER.ORIGINAL_TRANSLATOR, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },
  async create(data: Omit<OriginalTranslatorMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.ORIGINAL_TRANSLATOR, data);
  },
  async update(id: number, data: Partial<OriginalTranslatorMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.ORIGINAL_TRANSLATOR}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.ORIGINAL_TRANSLATOR, { ids });
  },
};
