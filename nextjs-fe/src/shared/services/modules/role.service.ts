import { apiClient } from '@/shared/api/client';
import { ENDPOINTS } from '@/shared/api';
import type {
  RoleMst,
  PaginatedResponse,
  ListQueryParams,
} from '@/shared/types/api';

export interface RoleListParams extends ListQueryParams {
  id?: number;
  name?: string;
  status?: number;
  is_active?: boolean;
  is_delete?: boolean;
}

export const roleService = {
  async list(params: RoleListParams = {}) {
    return apiClient.get<PaginatedResponse<RoleMst>>(ENDPOINTS.MASTER.ROLE, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<RoleMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.ROLE, data);
  },

  async update(id: number, data: Partial<RoleMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.ROLE}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.ROLE, { ids });
  },
};
