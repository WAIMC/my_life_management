/**
 * Media File Types
 * Updated to match MediaMgmt backend model
 */

export interface MediaFile {
  id: number;
  workspace_id: number | null;
  is_file: boolean;
  virtual_path: string;
  storage_path: string | null;
  original_name: string;
  extension: string | null;
  mime_type: string | null;
  size: number | null;
  minio_bucket: string | null;
  minio_object_key: string | null;
  minio_etag: string | null;
  url: string | null;
  width: number | null;
  height: number | null;
  duration: number | null;
  metadata: Record<string, any> | null;
  is_delete: boolean;
  created_by: number | null;
  updated_by: number | null;
  created_at: string;
  updated_at: string;
}

export interface UploadFileParams {
  file: File;
  parent_path?: string;
  workspace_id?: number;
  is_public?: boolean;
}

export interface ListFilesParams {
  parent_path?: string;
  is_file?: boolean;
  mime_type?: string;
  search?: string;
  order_by?: 'created_at' | 'original_name' | 'size';
  order_direction?: 'asc' | 'desc';
  per_page?: number;
  page?: number;
  file_type?: string;
  [key: string]: string | number | boolean | undefined;
}

export interface RenameFileParams {
  name: string;
}

export interface MoveFileParams {
  new_parent_path: string;
}

export interface DeleteFilesParams {
  ids: number[];
}

export interface CreateFolderParams {
  name: string;
  parent_path?: string;
  workspace_id?: number;
}

export interface CopyFilesParams {
  ids: number[];
  target_folder_path: string;
}

export interface UploadFileResponse {
  id: number;
  original_name: string;
  size: number;
  virtual_path: string;
}

export type FileType = 'images' | 'videos' | 'documents' | 'all';

export interface FileTypeFilter {
  label: string;
  value: FileType;
  icon?: string;
}
