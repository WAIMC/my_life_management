export interface GoogleDriveConfig {
  id: number;
  name: string;
  root_folder_id: string;
  is_active: boolean;
  status: number;
  has_valid_credentials: boolean;
  created_at: string;
  updated_at: string;
}

export interface UploadCredentialsParams {
  file: File;
  name: string;
  root_folder_id: string;
}

export interface UploadCredentialsResponse {
  id: number;
  name: string;
  message: string;
}
