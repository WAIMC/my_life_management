import { useState, useEffect, useCallback } from 'react';
import { 
  MediaFile, 
  ViewMode, 
  FilterOptions, 
  SortOptions, 
  PaginationState,
  FileManagerContextType 
} from '@/components/file-manager/types';
import { mediaFileService } from '@/services/media-file.service';
import toast from 'react-hot-toast';

export const useFileManager = (): FileManagerContextType => {
  const [currentPath, setCurrentPath] = useState('/');
  const [viewMode, setViewMode] = useState<ViewMode>('grid');
  const [files, setFiles] = useState<MediaFile[]>([]);
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

  const fetchFiles = useCallback(async () => {
    setIsLoading(true);
    try {
      const response = await mediaFileService.list({
        folder_path: currentPath === '/' ? undefined : currentPath,
        search: searchQuery || undefined,
        order_by: sortOptions.field === 'date' ? 'created_at' : sortOptions.field === 'name' ? 'original_name' : 'size',
        order_direction: sortOptions.order,
        per_page: pagination.pageSize,
        page: pagination.page,
        mime_type: filterOptions.type === 'all' ? undefined : 
                   filterOptions.type === 'images' ? 'image/%' :
                   filterOptions.type === 'videos' ? 'video/%' :
                   filterOptions.type === 'documents' ? 'application/%' : undefined
      });
      
      // Laravel Resource Collection standard structure: response.data contains { data: [], meta: {}, links: {} }
      const transformedFiles: MediaFile[] = (response.data.data || []).map((file: any) => ({
        id: String(file.id),
        drive_id: file.google_file_id,
        name: file.original_name,
        mime_type: file.mime_type,
        url: file.view_url || '',
        thumbnail_url: file.mime_type?.startsWith('image/') ? file.view_url : undefined,
        folder_path: file.folder_path || '/',
        size: file.size,
        owner_id: String(file.admin_mst_id),
        created_at: file.created_at,
        updated_at: file.updated_at,
        type: file.mime_type === 'application/vnd.google-apps.folder' ? 'folder' as const : 'file' as const
      }));
      
      setFiles(transformedFiles);
      
      // Update pagination from meta
      if (response.data.meta) {
        setPagination(prev => ({
          ...prev,
          total: response.data.meta.total || 0,
          totalPages: response.data.meta.last_page || 1
        }));
      }
    } catch (error) {
      toast.error('Không thể tải danh sách file');
      console.error('Error fetching files:', error);
      setFiles([]); // Clear files on error
    } finally {
      setIsLoading(false);
    }
  }, [currentPath, pagination.page, pagination.pageSize, filterOptions, sortOptions, searchQuery]);

  useEffect(() => {
    fetchFiles();
  }, [fetchFiles]);

  // Reset pagination when path or filters change
  useEffect(() => {
    setPagination(prev => ({ ...prev, page: 1 }));
  }, [currentPath, filterOptions, searchQuery]);

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
        return [...prev, fileId];
      } else {
        return prev.filter(id => id !== fileId);
      }
    });
  }, []);

  const selectAllFiles = useCallback((selected: boolean) => {
    if (selected) {
      setSelectedFiles(files.map(f => f.id));
    } else {
      setSelectedFiles([]);
    }
  }, [files]);

  const createFolder = useCallback(async (name: string) => {
    setIsLoading(true);
    try {
      await mediaFileService.createFolder({
        name,
        folder_path: currentPath === '/' ? undefined : currentPath
      });
      toast.success('Tạo thư mục thành công');
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể tạo thư mục');
      console.error('Error creating folder:', error);
    } finally {
      setIsLoading(false);
    }
  }, [currentPath, fetchFiles]);

  const uploadFiles = useCallback(async (filesToUpload: File[]) => {
    setIsLoading(true);
    try {
      // Upload sequentially for now
      for (const file of filesToUpload) {
        await mediaFileService.upload({ file });
      }
      toast.success(`Đã tải lên ${filesToUpload.length} file`);
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể tải lên file');
      console.error('Error uploading files:', error);
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const deleteFiles = useCallback(async (ids: string[]) => {
    setIsLoading(true);
    try {
      await mediaFileService.delete({ ids: ids.map(id => Number(id)) });
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      toast.success('Đã xóa file');
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể xóa file');
      console.error('Error deleting files:', error);
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const renameFile = useCallback(async (id: string, newName: string) => {
    setIsLoading(true);
    try {
      await mediaFileService.rename(Number(id), { new_name: newName });
      toast.success('Đã đổi tên file');
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể đổi tên file');
      console.error('Error renaming file:', error);
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const moveFiles = useCallback(async (ids: string[], targetPath: string) => {
    setIsLoading(true);
    try {
      for (const id of ids) {
        await mediaFileService.move(Number(id), { new_folder_path: targetPath });
      }
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      toast.success('Đã di chuyển file');
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể di chuyển file');
      console.error('Error moving files:', error);
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const copyFiles = useCallback(async (ids: string[], targetPath: string) => {
    setIsLoading(true);
    try {
      await mediaFileService.copy({
        ids: ids.map(id => Number(id)),
        target_folder_path: targetPath
      });
      toast.success('Đã sao chép file');
      await fetchFiles();
    } catch (error) {
      toast.error('Không thể sao chép file');
      console.error('Error copying files:', error);
    } finally {
      setIsLoading(false);
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
    copyFiles
  };
};
