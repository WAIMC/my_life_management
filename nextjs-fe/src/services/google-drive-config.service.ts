import { apiClient } from '@/lib/api-client';
import type { PaginatedResponse } from '@/lib/types/api';
import type {
  GoogleDriveConfig,
  UploadCredentialsParams,
  UploadCredentialsResponse,
} from '@/types/google-drive-config.types';

class GoogleDriveConfigService {
  private baseUrl = '/admin/google-drive-configs';

  async upload(params: UploadCredentialsParams): Promise<UploadCredentialsResponse> {
    const formData = new FormData();
    formData.append('file', params.file);
    formData.append('name', params.name);
    formData.append('root_folder_id', params.root_folder_id);

    const response = await apiClient.post<UploadCredentialsResponse>(
      `${this.baseUrl}/upload`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    return response.data;
  }

  async list(): Promise<PaginatedResponse<GoogleDriveConfig>> {
    const response = await apiClient.get<PaginatedResponse<GoogleDriveConfig>>(this.baseUrl);
    return response.data;
  }

  async getActive(): Promise<GoogleDriveConfig | null> {
    try {
      const response = await apiClient.get<GoogleDriveConfig>(`${this.baseUrl}/active`);
      return response.data;
    } catch (error) {
      return null;
    }
  }

  async activate(id: number): Promise<void> {
    await apiClient.put(`${this.baseUrl}/${id}/activate`, {});
  }

  async delete(ids: number[]): Promise<void> {
    await apiClient.delete(`${this.baseUrl}/delete`, { ids });
  }
}

export const googleDriveConfigService = new GoogleDriveConfigService();
