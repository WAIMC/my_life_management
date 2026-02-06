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
import { UploadStatus } from '@/shared/enums/enums';

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
  
  // State to track heavy file uploads for WebSocket notifications
  // Note: Not persisted to localStorage - if user refreshes/closes tab, 
  // MinIO lifecycle will auto-cleanup incomplete parts and user can re-upload if needed
  const [heavyUploads, setHeavyUploads] = useState<Array<{ roomId: string; fileName: string }>>([]);

  // Fetch files from API (only when path changes)
  const fetchFiles = useCallback(async (signal?: AbortSignal) => {
    setIsLoading(true);
    try {
      const response = await mediaFileService.list({
        parent_path: currentPath || '/'
      }, { signal });
      
      // If the signal is aborted, stop processing
      if (signal?.aborted) {
         return;
      }

      // Handle response format: Check if response itself is the array
      // based on logs: API Response: [{...}]
      let listData: unknown[] = [];
      
      if (Array.isArray(response)) {
        listData = response;
      } else if (response && typeof response === 'object' && 'data' in response && Array.isArray((response as {data: unknown[]}).data)) {
        listData = (response as {data: unknown[]}).data;
      } else {
        listData = [];
      }
      
      // Transform and cache raw data
      const transformedFiles: MediaFile[] = listData.map((fileData: unknown) => {
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
          type: !file.is_file ? FILE_TYPE.FOLDER : FILE_TYPE.FILE
        };
      });
      
      setRawFiles(transformedFiles);
    } catch {
      if (signal?.aborted) return;
      toast.error(t('listError'));
      setRawFiles([]);
    } finally {
      if (!signal?.aborted) {
        setIsLoading(false);
      }
    }
  }, [currentPath, t]);

  // Fetch files only when path changes
  useEffect(() => {
    const controller = new AbortController();
    fetchFiles(controller.signal);
    
    return () => {
      controller.abort();
    };
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
      setIsLoading(true);
      const heavyFileUploads: Array<{ roomId: string; fileName: string }> = [];
      
      // Process files in parallel instead of sequential for better performance
      const uploadPromises = filesToUpload.map(async (file) => {
        try {
          // Check if file already has temp metadata (from auto-upload)
          const fileWithMeta = file as File & { 
            tempKey?: string;
            isHeavyUploaded?: boolean;
            tempMetadata?: {
              original_name: string;
              extension: string;
              mime_type: string;
              size: number;
            };
          };

          if (fileWithMeta.tempKey && fileWithMeta.tempMetadata) {
            // File already uploaded to temp, just commit to official
            const result = await mediaFileService.upload({
              file, // Pass to satisfy type, won't be used
              parent_path: currentPath,
              key: fileWithMeta.tempKey,
              ...fileWithMeta.tempMetadata
            });
            
            console.log('[useFileManager] Upload result:', result);
            console.log('[useFileManager] result.status:', result.status);
            console.log('[useFileManager] result.room_id:', result.room_id);
            console.log('[useFileManager] UploadStatus.PROCESSING:', UploadStatus.PROCESSING);
            
            // Check if result is heavy upload (status = PROCESSING)
            if (result.status === UploadStatus.PROCESSING && result.room_id) {
              heavyFileUploads.push({ 
                roomId: result.room_id, 
                fileName: fileWithMeta.tempMetadata.original_name 
              });
              toast.success(result.message);
              return { type: 'heavy', fileName: fileWithMeta.tempMetadata.original_name };
            }
            // Light file (status = COMPLETED)
            return { type: 'immediate', fileName: fileWithMeta.tempMetadata.original_name };
          } else {
            // Fallback: Upload to temp first, then commit
            const metadata = await mediaFileService.uploadToMinio({ file });

            if (metadata) {
              // Call API to store (Move to Official)
              const result = await mediaFileService.upload({
                file, // Pass file just to satisfy type, but won't be used if key is present
                parent_path: currentPath,
                ...metadata
              });
              
              console.log('[useFileManager] Upload result (fallback):', result);
              console.log('[useFileManager] result.status:', result.status);
              console.log('[useFileManager] result.room_id:', result.room_id);
              
              // Check if result is heavy upload (status = PROCESSING)
              if (result.status === UploadStatus.PROCESSING && result.room_id) {
                heavyFileUploads.push({ 
                  roomId: result.room_id, 
                  fileName: metadata.original_name 
                });
                toast.success(result.message);
                return { type: 'heavy', fileName: metadata.original_name };
              }
              // Light file (status = COMPLETED)
              return { type: 'immediate', fileName: metadata.original_name };
            }
          }
          return { type: 'error', fileName: file.name };
        } catch (error) {
          console.error(`Failed to upload ${file.name}:`, error);
          const msg = error instanceof Error ? error.message : 'Unknown error';
          toast.error(`${file.name}: ${msg}`);
          return { type: 'error', fileName: file.name };
        }
      });
      
      // Wait for all uploads to complete
      const results = await Promise.all(uploadPromises);
      
      // Update heavy uploads state for parent component to subscribe to WebSocket
      if (heavyFileUploads.length > 0) {
        console.log('[useFileManager] Adding heavy uploads to state:', heavyFileUploads);
        setHeavyUploads(prev => {
          const updated = [...prev, ...heavyFileUploads];
          console.log('[useFileManager] Updated heavyUploads state:', updated);
          return updated;
        });
      }
      
      // Show success for files that were processed immediately (not heavy uploads)
      const immediateUploads = results.filter(r => r.type === 'immediate').length;
      const failedUploads = results.filter(r => r.type === 'error').length;
      
      if (immediateUploads > 0) {
        toast.success(`${t('uploadSuccess')} ${immediateUploads} file(s)`);
      }
      
      if (failedUploads > 0) {
        toast.error(`${failedUploads} file(s) failed to upload`);
      }
      
      // Note: Don't fetchFiles here for heavy uploads - will be refreshed when WSS notification arrives
      // Only fetch if there were immediate uploads
      if (immediateUploads > 0) {
        await fetchFiles();
      }
    } catch (error) {
      console.error("Upload error sequence:", error);
      // Extract message if possible
      const msg = error instanceof Error ? error.message : t('uploadError');
      toast.error(`${t('uploadError')}: ${msg}`);
      throw error;
    } finally {
      setIsLoading(false);
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

  // Clear a specific heavy upload from the list (after WSS notification received)
  const clearHeavyUpload = useCallback((roomId: string) => {
    setHeavyUploads(prev => prev.filter(upload => upload.roomId !== roomId));
  }, []);

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
    copyFiles,
    heavyUploads,
    clearHeavyUpload,
  };
};
