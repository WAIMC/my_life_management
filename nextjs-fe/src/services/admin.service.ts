import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type {
  AdminMst,
  PaginatedResponse,
  ListQueryParams,
  ApiResponse,
} from '@/lib/types/api';

/**
 * Admin Service
 * Handles all API calls related to admin management
 */

export interface AdminListParams extends ListQueryParams {
  id?: number;
  email?: string;
  user_name?: string;
  first_name?: string;
  last_name?: string;
  status?: number;
  is_active?: boolean;
  is_delete?: boolean;
  gender?: number;
}

export const adminService = {
  /**
   * Get paginated list of admins with filters
   */
  async list(params: AdminListParams = {}): Promise<ApiResponse<PaginatedResponse<AdminMst>>> {
    return apiClient.get<PaginatedResponse<AdminMst>>(ENDPOINTS.MASTER.ADMIN, params);
  },

  /**
   * Get single admin by ID
   */
  async getById(id: number): Promise<AdminMst | null> {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  /**
   * Create new admin
   */
  async create(data: Omit<AdminMst, 'id' | 'updated_at' | 'created_at'>): Promise<number> {
    const response = await apiClient.post<number>(ENDPOINTS.MASTER.ADMIN, data);
    return response.data;
  },

  /**
   * Update existing admin
   */
  async update(id: number, data: Partial<AdminMst>): Promise<number> {
    const response = await apiClient.put<number>(`${ENDPOINTS.MASTER.ADMIN}/${id}`, {
      id,
      ...data,
    });
    return response.data;
  },

  /**
   * Delete admin(s)
   */
  async delete(ids: number[]): Promise<void> {
    await apiClient.delete(ENDPOINTS.MASTER.ADMIN, { ids });
  },
};
