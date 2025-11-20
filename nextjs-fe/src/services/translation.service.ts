import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { TranslationMst, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export interface TranslationListParams extends ListQueryParams {
  id?: number;
  key?: string;
  value?: string;
  status?: number;
  is_active?: boolean;
}

export const translationService = {
  async list(params: TranslationListParams = {}) {
    return apiClient.get<PaginatedResponse<TranslationMst>>(ENDPOINTS.MASTER.TRANSLATION, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<TranslationMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.TRANSLATION, data);
  },

  async update(id: number, data: Partial<TranslationMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.TRANSLATION}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.TRANSLATION, { ids });
  },
};
