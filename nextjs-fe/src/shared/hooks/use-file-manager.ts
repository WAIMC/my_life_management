import { useState, useEffect, useCallback } from 'react';
import { 
  MediaFile, 
  ViewMode, 
  FilterOptions, 
  SortOptions, 
  PaginationState,
  FileManagerContextType 
} from '@/components/common/file-manager/types';
import { mediaFileService } from '@/shared/services/modules/media-file.service';
import toast from 'react-hot-toast';

export const useFileManager = (): FileManagerContextType => {
  const [currentPath, setCurrentPath] = useState('/');
  const [viewMode, setViewMode] = useState<ViewMode>('grid');
  const [rawFiles, setRawFiles] = useState<MediaFile[]>([]); // Cache raw data from API
  const [files, setFiles] = useState<MediaFile[]>([]); // Filtered/sorted files for display
  const [selectedFiles, setSelectedFiles] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  
  const [filterOptions, setFilterOptions] = useState<FilterOptions>({
    type: 'all'
  });
  
  const [sortOptions, setSortOptions] = useState<SortOptions>({
    field: 'date',
    order: 'desc'
  });

  const [pagination, setPagination] = useState<PaginationState>({
    page: 1,
    pageSize: 25,
    total: 0,
    totalPages: 0
  });

  // Fetch files from API (only when path changes)
  const fetchFiles = useCallback(async () => {
    setIsLoading(true);
    try {
      const response = await mediaFileService.list({
        parent_path: currentPath || '/'
        // No search, sort, or filter params - get ALL files and handle on client
      });
      
      // Transform and cache raw data
      const transformedFiles: MediaFile[] = (response.data || []).map((file: any) => ({
        id: String(file.id),
        drive_id: file.id,
        name: file.original_name,
        mime_type: file.mime_type,
        url: file.view_url || file.url || '',
        thumbnail_url: file.mime_type?.startsWith('image/') ? (file.view_url || file.url) : undefined,
        folder_path: file.folder_path || '/',
        size: file.size,
        owner_id: String(file.workspace_id || 0),
        created_at: file.created_at,
        updated_at: file.updated_at,
        type: file.is_file === false ? 'folder' as const : 'file' as const
      }));
      
      setRawFiles(transformedFiles); // Cache raw data
    } catch (error) {
      toast.error('Không thể tải danh sách file');
      console.error('❌ Error fetching files:', error);
      setRawFiles([]);
    } finally {
      setIsLoading(false);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [currentPath]); // Empty dependencies - uses current values from closure

  // Fetch files only when path changes
  useEffect(() => {
    fetchFiles();
  }, [currentPath, fetchFiles]);

  // Apply client-side filtering, sorting, and searching
  useEffect(() => {
    let processed = [...rawFiles];
    
    // 1. Apply search
    if (searchQuery) {
      processed = processed.filter(file => 
        file.name.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }
    
    // 2. Apply filter by type
    if (filterOptions.type !== 'all') {
      processed = processed.filter(file => {
        if (filterOptions.type === 'folders') return file.type === 'folder';
        if (filterOptions.type === 'images') return file.mime_type?.startsWith('image/');
        if (filterOptions.type === 'videos') return file.mime_type?.startsWith('video/');
        if (filterOptions.type === 'documents') {
          return file.type === 'file' && 
                 file.mime_type && 
                 !file.mime_type.startsWith('image/') && 
                 !file.mime_type.startsWith('video/');
        }
        return true;
      });
    }
    
    // 3. Apply sorting
    processed.sort((a, b) => {
      const multiplier = sortOptions.order === 'asc' ? 1 : -1;
      
      switch (sortOptions.field) {
        case 'name':
          return a.name.localeCompare(b.name) * multiplier;
        case 'date':
          return (new Date(a.created_at).getTime() - new Date(b.created_at).getTime()) * multiplier;
        case 'size':
          return (a.size - b.size) * multiplier;
        case 'type':
          return (a.mime_type || '').localeCompare(b.mime_type || '') * multiplier;
        default:
          return 0;
      }
    });
    
    setFiles(processed);
  }, [rawFiles, searchQuery, filterOptions, sortOptions]);

  // Reset pagination when filters change
  useEffect(() => {
    setPagination(prev => ({ ...prev, page: 1 }));
  }, [searchQuery, filterOptions]);

  // Clear selection when path changes
  useEffect(() => {
    setSelectedFiles([]);
  }, [currentPath]);

  const refreshFiles = useCallback(async () => {
    await fetchFiles();
  }, [fetchFiles]);

  const toggleFileSelection = useCallback((fileId: string, selected: boolean) => {
    setSelectedFiles(prev => {
      if (selected) {
        // Add to selection if not already selected
        return prev.includes(fileId) ? prev : [...prev, fileId];
      } else {
        // Remove from selection
        return prev.filter(id => id !== fileId);
      }
    });
  }, []);

  const selectAllFiles = useCallback((selected: boolean) => {
    if (selected) {
      // Select all files
      setSelectedFiles(files.map(f => f.id));
    } else {
      // Deselect all
      setSelectedFiles([]);
    }
  }, [files]);

  const createFolder = useCallback(async (name: string) => {
    try {
      await mediaFileService.createFolder({
        name,
        parent_path: currentPath // Send current path as parent_path
      });
      toast.success('Tạo thư mục thành công');
      // Refresh file list to show new folder
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể tạo thư mục');
      console.error('Error creating folder:', error);
    }
  }, [currentPath, fetchFiles]);

  const uploadFiles = useCallback(async (filesToUpload: File[]) => {
    try {
      // Upload sequentially for now
      for (const file of filesToUpload) {
        await mediaFileService.upload({ 
          file,
          parent_path: currentPath // Upload to current folder
        });
      }
      toast.success(`Đã tải lên ${filesToUpload.length} file`);
      // Refresh file list to show uploaded files
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể tải lên file');
      console.error('Error uploading files:', error);
    }
  }, [currentPath, fetchFiles]);

  const deleteFiles = useCallback(async (ids: string[]) => {
    try {
      await mediaFileService.delete({ ids: ids.map(id => Number(id)) });
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      toast.success('Đã xóa file');
      // Refresh file list
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể xóa file');
      console.error('Error deleting files:', error);
    }
  }, [fetchFiles]);

  const renameFile = useCallback(async (id: string, newName: string) => {
    try {
      await mediaFileService.rename(Number(id), { name: newName });
      toast.success('Đã đổi tên file');
      // Refresh file list
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể đổi tên file');
      console.error('Error renaming file:', error);
    }
  }, [fetchFiles]);

  const moveFiles = useCallback(async (ids: string[], targetPath: string) => {
    try {
      for (const id of ids) {
        await mediaFileService.move(Number(id), { new_parent_path: targetPath });
      }
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      toast.success('Đã di chuyển file');
      // Refresh file list
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể di chuyển file');
      console.error('Error moving files:', error);
    }
  }, [fetchFiles]);

  const copyFiles = useCallback(async (ids: string[], targetPath: string) => {
    try {
      await mediaFileService.copy({
        ids: ids.map(id => Number(id)),
        target_folder_path: targetPath
      });
      toast.success('Đã sao chép file');
      // Refresh file list
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể sao chép file');
      console.error('Error copying files:', error);
    }
  }, [fetchFiles]);

  return {
    currentPath,
    setCurrentPath,
    viewMode,
    setViewMode,
    files,
    setFiles,
    selectedFiles,
    setSelectedFiles,
    isLoading,
    setIsLoading,
    searchQuery,
    setSearchQuery,
    filterOptions,
    setFilterOptions,
    sortOptions,
    setSortOptions,
    pagination,
    setPagination,
    refreshFiles,
    toggleFileSelection,
    selectAllFiles,
    createFolder,
    uploadFiles,
    deleteFiles,
    renameFile,
    moveFiles,
    filterType: filterOptions.type,
    setFilterType: (type: string) => setFilterOptions(prev => ({ ...prev, type: type as any })),
    sortBy: sortOptions.field,
    setSortBy: (field: any) => setSortOptions(prev => ({ ...prev, field })),
    copyFiles
  };
};
