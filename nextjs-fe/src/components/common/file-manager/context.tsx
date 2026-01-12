'use client';

import { useState, createContext, useContext, ReactNode } from 'react';
import type { MediaFile, ViewMode, SortField, FilterType, FileManagerContextType } from '@/shared/types/file-manager.types';
import { FILE_MANAGER_SORT_FIELDS, VIEW_MODE, FILTER_TYPE } from '@/shared/config/constant';
import { PAGINATION } from '@/shared/config/constant';

const FileManagerContext = createContext<FileManagerContextType | undefined>(undefined);

export const FileManagerProvider = ({ children }: { children: ReactNode }) => {
  const [currentPath, setCurrentPath] = useState<string>('/');
  const [viewMode, setViewMode] = useState<ViewMode>(VIEW_MODE.GRID);
  const [files, setFiles] = useState<MediaFile[]>([]);
  const [selectedFiles, setSelectedFiles] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [filterType, setFilterType] = useState<FilterType>(FILTER_TYPE.ALL);
  const [sortBy, setSortBy] = useState<SortField>(FILE_MANAGER_SORT_FIELDS.NAME);

  const value: FileManagerContextType = {
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
    pagination: {
        page: PAGINATION.DEFAULT_PAGE,
        pageSize: PAGINATION.DEFAULT_PER_PAGE,
        total: files.length,
        totalPages: PAGINATION.DEFAULT_TOTAL_PAGES
    },
    searchQuery,
    setSearchQuery,
    filterType,
    setFilterType: (type: string) => setFilterType(type as FilterType),
    sortBy,
    setSortBy,
  };

  return (
    <FileManagerContext.Provider value={value}>
      {children}
    </FileManagerContext.Provider>
  );
};

export const useFileManager = () => {
  const context = useContext(FileManagerContext);
  if (context === undefined) {
    throw new Error('hooks.useFileManagerError');
  }
  return context;
};
