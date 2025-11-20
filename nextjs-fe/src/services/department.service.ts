import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type {
  DepartmentMst,
  PaginatedResponse,
  ListQueryParams,
} from '@/lib/types/api';

export interface DepartmentListParams extends ListQueryParams {
  id?: number;
  name?: string;
  status?: number;
  is_active?: boolean;
  is_delete?: boolean;
}

export const departmentService = {
  async list(params: DepartmentListParams = {}) {
    return apiClient.get<PaginatedResponse<DepartmentMst>>(ENDPOINTS.MASTER.DEPARTMENT, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<DepartmentMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.DEPARTMENT, data);
  },

  async update(id: number, data: Partial<DepartmentMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.DEPARTMENT}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.DEPARTMENT, { ids });
  },
};
