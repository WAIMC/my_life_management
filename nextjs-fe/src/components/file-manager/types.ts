export type FileType = 'file' | 'folder';
export type ViewMode = 'grid' | 'list';
export type FileOperationType = 'rename' | 'move' | 'copy' | 'delete' | 'upload' | 'create_folder';
export type SortField = 'name' | 'date' | 'size' | 'type';
export type SortOrder = 'asc' | 'desc';
export type FilterType = 'all' | 'images' | 'videos' | 'documents' | 'folders';

export interface MediaFile {
  id: string;
  drive_id: string;
  name: string;
  mime_type: string;
  url: string;
  thumbnail_url?: string;
  folder_path: string;
  parent_id?: string;
  size: number;
  owner_id: string;
  created_at: string;
  updated_at: string;
  type: FileType;
  tags?: string[];
  isSelected?: boolean;
}

export interface Folder {
  id: string;
  name: string;
  path: string;
  parent_id?: string;
  children?: Folder[];
  file_count?: number;
}

export interface PaginationState {
  page: number;
  pageSize: number;
  total: number;
  totalPages: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  pagination: PaginationState;
}

export interface UploadProgress {
  fileId: string;
  fileName: string;
  progress: number; // 0-100
  status: 'pending' | 'uploading' | 'completed' | 'error';
  error?: string;
}

export interface UploadState {
  files: File[];
  progresses: UploadProgress[];
  isUploading: boolean;
}

export interface FilterOptions {
  type: FilterType;
  dateFrom?: Date;
  dateTo?: Date;
  minSize?: number;
  maxSize?: number;
  tags?: string[];
}

export interface SortOptions {
  field: SortField;
  order: SortOrder;
}

export interface FileOperation {
  type: FileOperationType;
  fileIds: string[];
  targetPath?: string;
  newName?: string;
  metadata?: Record<string, unknown>;
}

export interface FileManagerContextType {
  currentPath: string;
  setCurrentPath: (path: string) => void;
  viewMode: ViewMode;
  setViewMode: (mode: ViewMode) => void;
  files: MediaFile[];
  setFiles: (files: MediaFile[]) => void;
  selectedFiles: string[];
  setSelectedFiles: (ids: string[]) => void;
  isLoading: boolean;
  setIsLoading: (loading: boolean) => void;
  searchQuery: string;
  setSearchQuery: (query: string) => void;
  filterOptions: FilterOptions;
  setFilterOptions: (options: FilterOptions) => void;
  sortOptions: SortOptions;
  setSortOptions: (options: SortOptions) => void;
  pagination: PaginationState;
  setPagination: (pagination: PaginationState) => void;
  
  // Actions
  refreshFiles: () => Promise<void>;
  toggleFileSelection: (fileId: string, selected: boolean) => void;
  selectAllFiles: (selected: boolean) => void;
  createFolder: (name: string) => Promise<void>;
  uploadFiles: (files: File[]) => Promise<void>;
  deleteFiles: (ids: string[]) => Promise<void>;
  renameFile: (id: string, newName: string) => Promise<void>;
  moveFiles: (ids: string[], targetPath: string) => Promise<void>;
  copyFiles: (ids: string[], targetPath: string) => Promise<void>;
}

