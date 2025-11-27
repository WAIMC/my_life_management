/**
 * Media File Types
 */

export interface MediaFile {
  id: number;
  admin_mst_id: number;
  google_file_id: string;
  original_name: string;
  extension: string;
  mime_type: string;
  size: number;
  human_size: string;
  folder_path: string | null;
  is_public: boolean;
  metadata: Record<string, any> | null;
  status: number;
  view_url: string;
  download_url: string;
  created_at: string;
  updated_at: string;
}

export interface UploadFileParams {
  file: File;
  is_public?: boolean;
}

export interface ListFilesParams {
  folder_path?: string;
  file_type?: 'images' | 'videos' | 'documents';
  mime_type?: string;
  search?: string;
  status?: number;
  order_by?: 'created_at' | 'original_name' | 'size';
  order_direction?: 'asc' | 'desc';
  per_page?: number;
  page?: number;
}

export interface RenameFileParams {
  new_name: string;
}

export interface MoveFileParams {
  new_folder_path: string;
}

export interface DeleteFilesParams {
  ids: number[];
}

export interface CreateFolderParams {
  name: string;
  folder_path?: string;
}

export interface CopyFilesParams {
  ids: number[];
  target_folder_path: string;
}

export interface UploadFileResponse {
  id: number;
  google_file_id: string;
  original_name: string;
  size: number;
  folder_path: string;
}

export type FileType = 'images' | 'videos' | 'documents' | 'all';

export interface FileTypeFilter {
  label: string;
  value: FileType;
  icon?: string;
}
