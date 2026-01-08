import { apiClient } from '@/shared/api/client';
import { ENDPOINTS } from '@/shared/api';
import type { SliderMgmt, PaginatedResponse, ListQueryParams } from '@/shared/types/api';

export const sliderService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<SliderMgmt>>(ENDPOINTS.MANAGEMENT.SLIDER, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data.data[0] || null;
  },
  async create(data: Omit<SliderMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.SLIDER, data);
  },
  async update(id: number, data: Partial<SliderMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.SLIDER}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.SLIDER, { ids });
  },
};
