/**
 * Upload Component Types
 */

export interface FileUploadProps {
  value?: string;
  onChange: (url: string) => void;
  accept?: string;
  maxSize?: number; // in MB
  className?: string;
  disabled?: boolean;
}

export interface UploadError {
  message: string;
  code?: string;
}
