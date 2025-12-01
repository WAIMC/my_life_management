/**
 * Media File Service
 * Handles all API calls for media file management
 */

import { apiClient } from '@/lib/api-client';
import type { PaginatedResponse } from '@/lib/types/api';
import type {
  MediaFile,
  UploadFileParams,
  ListFilesParams,
  RenameFileParams,
  MoveFileParams,
  DeleteFilesParams,
  UploadFileResponse,
} from '@/types/media-file.types';

class MediaFileService {
  private baseUrl = '/admin/media-mgmt';

  /**
   * Upload file to server
   */
  async upload(params: UploadFileParams): Promise<number> {
    const formData = new FormData();
    formData.append('file', params.file);
    
    if (params.parent_path) {
      formData.append('parent_path', params.parent_path);
    }
    
    if (params.workspace_id !== undefined) {
      formData.append('workspace_id', params.workspace_id.toString());
    }

    const response = await apiClient.post<number>(
      `${this.baseUrl}/store`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    return response.data;
  }

  /**
   * Get list of media files
   */
  async list(params?: ListFilesParams): Promise<PaginatedResponse<MediaFile>> {
    const response = await apiClient.get<PaginatedResponse<MediaFile>>(
      `${this.baseUrl}/list`,
      params
    );
    return response.data;
  }

  /**
   * Get single media file by ID
   */
  async get(id: number): Promise<MediaFile> {
    const response = await apiClient.get<MediaFile>(`${this.baseUrl}/${id}`);
    return response.data;
  }

  /**
   * Get file view URL
   */
  getViewUrl(id: number): string {
    return `${this.baseUrl}/${id}/view`;
  }

  /**
   * Get file download URL
   */
  getDownloadUrl(id: number): string {
    return `${this.baseUrl}/${id}/download`;
  }

  /**
   * Download file
   */
  async download(id: number, filename?: string): Promise<void> {
    const url = this.getDownloadUrl(id);
    const response = await fetch(url);
    const blob = await response.blob();
    
    // Create download link
    const downloadUrl = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = filename || `file-${id}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(downloadUrl);
  }

  /**
   * Rename file or folder
   */
  async rename(id: number, params: RenameFileParams): Promise<number> {
    const response = await apiClient.put<number>(
      `${this.baseUrl}/update/${id}`,
      { name: params.name }
    );
    return response.data;
  }

  /**
   * Move file to different folder
   */
  async move(id: number, params: MoveFileParams): Promise<number> {
    const response = await apiClient.put<number>(
      `${this.baseUrl}/update/${id}`,
      { new_parent_path: params.new_parent_path }
    );
    return response.data;
  }

  /**
   * Delete files
   */
  async delete(params: DeleteFilesParams): Promise<void> {
    // For batch delete, use first ID in route and send all IDs in body
    const firstId = params.ids[0];
    await apiClient.delete(
      `${this.baseUrl}/delete/${firstId}`,
      { ids: params.ids }
    );
  }

  /**
   * Delete single file
   */
  async deleteSingle(id: number): Promise<void> {
    await apiClient.delete(`${this.baseUrl}/delete/${id}`);
  }

  /**
   * Get file type icon based on MIME type
   */
  getFileTypeIcon(mimeType: string): string {
    if (mimeType.startsWith('image/')) return '🖼️';
    if (mimeType.startsWith('video/')) return '🎥';
    if (mimeType.includes('pdf')) return '📄';
    if (mimeType.includes('word') || mimeType.includes('document')) return '📝';
    if (mimeType.includes('sheet') || mimeType.includes('excel')) return '📊';
    return '📁';
  }

  /**
   * Format file size to human readable format
   */
  formatFileSize(bytes: number): string {
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    let size = bytes;
    let unitIndex = 0;
    
    while (size >= 1024 && unitIndex < units.length - 1) {
      size /= 1024;
      unitIndex++;
    }
    
    return `${size.toFixed(2)} ${units[unitIndex]}`;
  }

  /**
   * Create folder
   */
  async createFolder(params: import('@/types/media-file.types').CreateFolderParams): Promise<number> {
    const response = await apiClient.post<number>(
      `${this.baseUrl}/store`,
      {
        name: params.name,
        parent_path: params.parent_path,
        workspace_id: params.workspace_id,
      }
    );
    return response.data;
  }

  /**
   * List folders
   */
  async listFolders(params?: import('@/types/media-file.types').ListFilesParams): Promise<PaginatedResponse<MediaFile>> {
    const response = await apiClient.get<PaginatedResponse<MediaFile>>(
      `${this.baseUrl}/list`,
      { ...params, is_file: false }
    );
    return response.data;
  }

  /**
   * Copy files to different folder
   */
  async copy(params: import('@/types/media-file.types').CopyFilesParams): Promise<{ copied_count: number; copied_ids: number[] }> {
    // Note: Copy functionality not implemented in backend yet
    // This is a placeholder for future implementation
    throw new Error('Copy functionality not yet implemented in backend');
  }
}

export const mediaFileService = new MediaFileService();
