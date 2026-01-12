import { useState, useEffect, useCallback } from 'react';
import { 
  MediaFile, 
  ViewMode, 
  FilterOptions, 
  SortOptions, 
  PaginationState,
  FileManagerContextType,
  FilterType,
  SortField
} from '@/shared/types/file-manager.types';
import { mediaFileService } from '@/shared/services/modules/media-file.service';
import toast from 'react-hot-toast';
import { FILTER_TYPE, FILE_MANAGER_SORT_FIELDS, SORT_ORDER, INITIAL_PAGINATION, FILE_TYPE, MIME_TYPE_PREFIX, PAGINATION } from '@/shared/config/constant';
import { useTranslations } from 'next-intl';

export const useFileManager = (): FileManagerContextType => {
  const t = useTranslations('fileManager');
  const [currentPath, setCurrentPath] = useState('/');
  const [viewMode, setViewMode] = useState<ViewMode>('grid');
  const [rawFiles, setRawFiles] = useState<MediaFile[]>([]);
  const [files, setFiles] = useState<MediaFile[]>([]);
  const [selectedFiles, setSelectedFiles] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  
  const [filterOptions, setFilterOptions] = useState<FilterOptions>({
    type: FILTER_TYPE.ALL
  });
  
  const [sortOptions, setSortOptions] = useState<SortOptions>({
    field: FILE_MANAGER_SORT_FIELDS.DATE,
    order: SORT_ORDER.DESC
  });

  const [pagination, setPagination] = useState<PaginationState>(INITIAL_PAGINATION);

  // Fetch files from API (only when path changes)
  const fetchFiles = useCallback(async () => {
    setIsLoading(true);
    try {
      const response = await mediaFileService.list({
        parent_path: currentPath || '/'
        // No search, sort, or filter params - get ALL files and handle on client
      });
      
      // Transform and cache raw data
      const transformedFiles: MediaFile[] = ((response.data || []) as unknown[]).map((fileData: unknown) => {
        const file = fileData as Record<string, unknown>;
        const mimeType = (file.mime_type as string | null | undefined) || '';
        return {
          id: String(file.id),
          drive_id: String(file.id),
          name: file.original_name as string,
          mime_type: mimeType,
          url: (file.view_url as string) || (file.url as string) || '',
          thumbnail_url: mimeType?.startsWith(MIME_TYPE_PREFIX.IMAGE) ? ((file.view_url as string) || (file.url as string)) : undefined,
          folder_path: (file.folder_path as string) || '/',
          size: file.size as number,
          owner_id: String(file.workspace_id || 0),
          created_at: file.created_at as string,
          updated_at: file.updated_at as string,
          type: file.is_file === false ? FILE_TYPE.FOLDER : FILE_TYPE.FILE
        };
      });
      
      setRawFiles(transformedFiles);
    } catch {
      toast.error(t('listError'));
      setRawFiles([]);
    } finally {
      setIsLoading(false);
    }
  }, [currentPath, t]);

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
    if (filterOptions.type !== FILTER_TYPE.ALL) {
      processed = processed.filter(file => {
        if (filterOptions.type === FILTER_TYPE.FOLDERS) return file.type === FILE_TYPE.FOLDER;
        if (filterOptions.type === FILTER_TYPE.IMAGES) return file.mime_type?.startsWith(MIME_TYPE_PREFIX.IMAGE);
        if (filterOptions.type === FILTER_TYPE.VIDEOS) return file.mime_type?.startsWith(MIME_TYPE_PREFIX.VIDEO);
        if (filterOptions.type === FILTER_TYPE.DOCUMENTS) {
          return file.type === FILE_TYPE.FILE && 
                file.mime_type && 
                !file.mime_type.startsWith(MIME_TYPE_PREFIX.IMAGE) && 
                !file.mime_type.startsWith(MIME_TYPE_PREFIX.VIDEO);
        }
        return true;
      });
    }
    
    // 3. Apply sorting
    processed.sort((a, b) => {
      const multiplier = sortOptions.order === SORT_ORDER.ASC ? 1 : -1;
      
      switch (sortOptions.field) {
        case FILE_MANAGER_SORT_FIELDS.NAME:
          return a.name.localeCompare(b.name) * multiplier;
        case FILE_MANAGER_SORT_FIELDS.DATE:
          return (new Date(a.created_at).getTime() - new Date(b.created_at).getTime()) * multiplier;
        case FILE_MANAGER_SORT_FIELDS.SIZE:
          return (a.size - b.size) * multiplier;
        case FILE_MANAGER_SORT_FIELDS.TYPE:
          return (a.mime_type || '').localeCompare(b.mime_type || '') * multiplier;
        default:
          return 0;
      }
    });
    
    setFiles(processed);
  }, [rawFiles, searchQuery, filterOptions, sortOptions]);

  // Reset pagination when filters change
  useEffect(() => {
    setPagination(prev => ({ ...prev, page: PAGINATION.DEFAULT_PAGE }));
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
      toast.success(t('createFolderSuccess'));
      await fetchFiles();
    } catch {
      toast.error(t('createFolderError'));
    }
  }, [currentPath, fetchFiles, t]);

  const uploadFiles = useCallback(async (filesToUpload: File[]) => {
    try {
      for (const file of filesToUpload) {
        await mediaFileService.upload({ 
          file,
          parent_path: currentPath
        });
      }
      toast.success(`${t('uploadSuccess')} ${filesToUpload.length} file`);
      await fetchFiles();
    } catch {
      toast.error(t('uploadError'));
    }
  }, [currentPath, fetchFiles, t]);

  const deleteFiles = useCallback(async (ids: string[]) => {
    try {
      await mediaFileService.delete({ ids: ids.map(id => Number(id)) });
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      toast.success(t('deleteSuccess'));
      await fetchFiles();
    } catch {
      toast.error(t('deleteError'));
    }
  }, [fetchFiles, t]);

  const renameFile = useCallback(async (id: string, newName: string) => {
    try {
      await mediaFileService.rename(Number(id), { name: newName });
      toast.success(t('renameSuccess'));
      await fetchFiles();
    } catch {
      toast.error(t('renameError'));
    }
  }, [fetchFiles, t]);

  const moveFiles = useCallback(async (ids: string[], targetPath: string) => {
    try {
      for (const id of ids) {
        await mediaFileService.move(Number(id), { new_parent_path: targetPath });
      }
      setSelectedFiles(prev => prev.filter(id => !ids.includes(id)));
      toast.success(t('moveSuccess'));
      await fetchFiles();
    } catch {
      toast.error(t('moveError'));
    }
  }, [fetchFiles, t]);

  const copyFiles = useCallback(async (ids: string[], targetPath: string) => {
    try {
      await mediaFileService.copy({
        ids: ids.map(id => Number(id)),
        target_folder_path: targetPath
      });
      toast.success(t('copySuccess'));
      await fetchFiles();
    } catch {
      toast.error(t('copyError'));
    }
  }, [fetchFiles, t]);

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
    setFilterType: (type: string) => setFilterOptions(prev => ({ ...prev, type: type as FilterType })),
    sortBy: sortOptions.field,
    setSortBy: (field: SortField) => setSortOptions(prev => ({ ...prev, field })),
    copyFiles
  };
};
