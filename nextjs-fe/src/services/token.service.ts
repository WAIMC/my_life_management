import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { TokenMst, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export interface TokenListParams extends ListQueryParams {
  id?: number;
  token?: string;
  admin_mst_id?: number;
  status?: number;
  is_active?: boolean;
}

export const tokenService = {
  async list(params: TokenListParams = {}) {
    return apiClient.get<PaginatedResponse<TokenMst>>(`${ENDPOINTS.MASTER.TOKEN}/list`, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<TokenMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(`${ENDPOINTS.MASTER.TOKEN}/store`, data);
  },

  async update(id: number, data: Partial<TokenMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.TOKEN}/update/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(`${ENDPOINTS.MASTER.TOKEN}/delete`, { ids });
  },
};
