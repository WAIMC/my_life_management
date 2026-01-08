import { apiClient } from '@/shared/api/client';
import { ENDPOINTS } from '@/shared/api';
import type {
  FeatureMst,
  PaginatedResponse,
  ListQueryParams,
} from '@/shared/types/api';

export interface FeatureListParams extends ListQueryParams {
  id?: number;
  name?: string;
  status?: number;
  is_active?: boolean;
  is_delete?: boolean;
}

export const featureService = {
  async list(params: FeatureListParams = {}) {
    return apiClient.get<PaginatedResponse<FeatureMst>>(ENDPOINTS.MASTER.FEATURE, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<FeatureMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.FEATURE, data);
  },

  async update(id: number, data: Partial<FeatureMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.FEATURE}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.FEATURE, { ids });
  },
};
