import { useState, useEffect, useCallback } from 'react';
import { 
  MediaFile, 
  ViewMode, 
  FilterOptions, 
  SortOptions, 
  PaginationState,
  FileManagerContextType 
} from '@/components/file-manager/types';
import { fileService } from '@/services/file-mock-service';
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
      const response = await fileService.getFiles(
        currentPath,
        pagination,
        filterOptions,
        sortOptions,
        searchQuery
      );
      setFiles(response.data);
      setPagination(prev => ({
        ...prev,
        ...response.pagination
      }));
    } catch (error) {
      console.error('Error fetching files:', error);
      toast.error('Không thể tải danh sách file');
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
      await fileService.createFolder(name, currentPath);
      await fetchFiles();
    } finally {
      setIsLoading(false);
    }
  }, [currentPath, fetchFiles]);

  const uploadFiles = useCallback(async (filesToUpload: File[]) => {
    setIsLoading(true);
    try {
      // Upload sequentially for now
      for (const file of filesToUpload) {
        await fileService.uploadFile(file, currentPath);
      }
      await fetchFiles();
    } finally {
      setIsLoading(false);
    }
  }, [currentPath, fetchFiles]);

  const deleteFiles = useCallback(async (ids: string[]) => {
    setIsLoading(true);
    try {
      await fileService.deleteFiles(ids);
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      await fetchFiles();
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const renameFile = useCallback(async (id: string, newName: string) => {
    setIsLoading(true);
    try {
      await fileService.renameFile(id, newName);
      await fetchFiles();
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const moveFiles = useCallback(async (ids: string[], targetPath: string) => {
    setIsLoading(true);
    try {
      await fileService.moveFiles(ids, targetPath);
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      await fetchFiles();
    } finally {
      setIsLoading(false);
    }
  }, [fetchFiles]);

  const copyFiles = useCallback(async (ids: string[], targetPath: string) => {
    setIsLoading(true);
    try {
      await fileService.copyFiles(ids, targetPath);
      await fetchFiles();
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
