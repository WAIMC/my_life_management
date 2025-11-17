export type FileType = 'file' | 'folder';
export type ViewMode = 'grid' | 'list';

export interface MediaFile {
  id: string;
  drive_id: string;
  name: string;
  mime_type: string;
  url: string;
  folder_path: string;
  size: number;
  owner_id: string;
  created_at: string;
  updated_at: string;
  type: FileType;
  isSelected?: boolean;
}

export interface Folder {
  id: string;
  name: string;
  path: string;
  children?: Folder[];
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
  filterType: string;
  setFilterType: (type: string) => void;
  sortBy: 'name' | 'date' | 'size' | 'type';
  setSortBy: (sort: 'name' | 'date' | 'size' | 'type') => void;
}
