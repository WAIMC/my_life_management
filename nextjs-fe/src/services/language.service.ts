import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { LanguageMst, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export interface LanguageListParams extends ListQueryParams {
  id?: number;
  name?: string;
  code?: string;
  status?: number;
  is_active?: boolean;
}

export const languageService = {
  async list(params: LanguageListParams = {}) {
    return apiClient.get<PaginatedResponse<LanguageMst>>(ENDPOINTS.MASTER.LANGUAGE, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<LanguageMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.LANGUAGE, data);
  },

  async update(id: number, data: Partial<LanguageMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.LANGUAGE}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.LANGUAGE, { ids });
  },
};
