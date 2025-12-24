import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { PolicyDepartmentMst, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const policyDepartmentService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<PolicyDepartmentMst>>(`${ENDPOINTS.MASTER.POLICY_DEPARTMENT}/list`, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 } as any);
    return response.data.data[0] || null;
  },
  async create(data: Omit<PolicyDepartmentMst, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(`${ENDPOINTS.MASTER.POLICY_DEPARTMENT}/store`, data);
  },
  async update(id: number, data: Partial<PolicyDepartmentMst>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.POLICY_DEPARTMENT}/update/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(`${ENDPOINTS.MASTER.POLICY_DEPARTMENT}/delete`, { ids });
  },
};
