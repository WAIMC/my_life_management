import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { SettingLinkMgmt, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export const settingLinkService = {
  async list(params: ListQueryParams = {}) {
    return apiClient.get<PaginatedResponse<SettingLinkMgmt>>(ENDPOINTS.MANAGEMENT.SETTING_LINK, params);
  },
  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },
  async create(data: Omit<SettingLinkMgmt, 'id' | 'updated_at' | 'created_at'>) {
    return apiClient.post<number>(ENDPOINTS.MANAGEMENT.SETTING_LINK, data);
  },
  async update(id: number, data: Partial<SettingLinkMgmt>) {
    return apiClient.put<number>(`${ENDPOINTS.MANAGEMENT.SETTING_LINK}/${id}`, { id, ...data });
  },
  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MANAGEMENT.SETTING_LINK, { ids });
  },
};
