/**
 * Media Upload Component Types
 */

import type { UploadFileResponse } from './media-file.types';

export interface FileUploadProps {
  onUploadSuccess?: (file: UploadFileResponse | number) => void;
  onUploadError?: (error: Error) => void;
  accept?: string;
  maxSize?: number; // in bytes
  isPublic?: boolean;
}
