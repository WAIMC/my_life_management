/**
 * Media Service
 * Service for Media Management CRUD operations (MinIO-based File Manager)
 */

import { createCrudService } from '@/services/crud-service';
import { ENDPOINTS } from '@/constants/api-endpoints';

// TODO: Add MediaMgmt type to @/lib/types/api.ts
export const mediaService = createCrudService<any>({
  baseUrl: ENDPOINTS.MEDIA,
  endpoints: {
    list: '/list',
    create: '/store',
    update: '/update',
    delete: '/delete',
  },
});

// Additional media-specific methods
export const mediaServiceExtended = {
  ...mediaService,

  /**
   * Upload media file
   */
  async upload(file: File, metadata?: Record<string, any>): Promise<number> {
    const formData = new FormData();
    formData.append('file', file);
    if (metadata) {
      Object.entries(metadata).forEach(([key, value]) => {
        formData.append(key, String(value));
      });
    }
    return mediaService.create(formData as any);
  },

  /**
   * Get media URL
   */
  getMediaUrl(path: string): string {
    return `${ENDPOINTS.MEDIA}/${path}`;
  },
};
