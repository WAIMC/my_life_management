'use client';

import { createContext, useContext, ReactNode, useState } from 'react';
import { FileManagerContextType, ViewMode, MediaFile } from './types';

const FileManagerContext = createContext<FileManagerContextType | undefined>(undefined);

export const FileManagerProvider = ({ children }: { children: ReactNode }) => {
  const [currentPath, setCurrentPath] = useState<string>('/');
  const [viewMode, setViewMode] = useState<ViewMode>('grid');
  const [files, setFiles] = useState<MediaFile[]>([]);
  const [selectedFiles, setSelectedFiles] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [filterType, setFilterType] = useState('all');
  const [sortBy, setSortBy] = useState<'name' | 'date' | 'size' | 'type'>('name');

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
    searchQuery,
    setSearchQuery,
    filterType,
    setFilterType,
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
    throw new Error('useFileManager must be used within FileManagerProvider');
  }
  return context;
};
