'use client';

import { useState, useMemo } from 'react';
import { Sidebar } from './sidebar';
import { Breadcrumb } from './breadcrumb';
import { Toolbar } from './toolbar';
import { FileGrid } from './file-grid';
import { FileList } from './file-list';
import { PreviewModal } from './preview-modal';
import type { MediaFile, ViewMode } from './types';
import { filterFiles, sortFiles } from './utils';
import toast from 'react-hot-toast';

// Mock data for demo
const MOCK_FILES: MediaFile[] = [
  {
    id: '1',
    drive_id: 'drive_1',
    name: 'Ảnh du lịch 1.jpg',
    mime_type: 'image/jpeg',
    url: 'https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=500&h=500&fit=crop',
    folder_path: '/images/2025',
    size: 2500000,
    owner_id: 'user_1',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    type: 'file',
  },
  {
    id: '2',
    drive_id: 'drive_2',
    name: 'Ảnh du lịch 2.jpg',
    mime_type: 'image/jpeg',
    url: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&h=500&fit=crop',
    folder_path: '/images/2025',
    size: 3000000,
    owner_id: 'user_1',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    type: 'file',
  },
  {
    id: '3',
    drive_id: 'drive_3',
    name: 'Tài liệu.pdf',
    mime_type: 'application/pdf',
    url: '#',
    folder_path: '/documents',
    size: 1500000,
    owner_id: 'user_1',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    type: 'file',
  },
  {
    id: '4',
    drive_id: 'drive_4',
    name: 'Video giới thiệu.mp4',
    mime_type: 'video/mp4',
    url: 'https://www.w3schools.com/html/mov_bbb.mp4',
    folder_path: '/videos',
    size: 50000000,
    owner_id: 'user_1',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    type: 'file',
  },
  {
    id: '5',
    drive_id: 'drive_5',
    name: 'Project files',
    mime_type: 'folder',
    url: '',
    folder_path: '/projects',
    size: 0,
    owner_id: 'user_1',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    type: 'folder',
  },
];

export const FileManager = () => {
  const [currentPath, setCurrentPath] = useState('/');
  const [viewMode, setViewMode] = useState<ViewMode>('grid');
  const [selectedFiles, setSelectedFiles] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [filterType] = useState('all');
  const [sortBy] = useState<'name' | 'date' | 'size' | 'type'>('name');
  const [previewFile, setPreviewFile] = useState<MediaFile | null>(null);
  const [sidebarOpen, setSidebarOpen] = useState(true);

  // Filter and sort files
  const processedFiles = useMemo(() => {
    let files = [...MOCK_FILES];
    files = filterFiles(files, searchQuery, filterType);
    files = sortFiles(files, sortBy);
    return files;
  }, [searchQuery, filterType, sortBy]);

  const handleSelectFile = (fileId: string, selected: boolean) => {
    setSelectedFiles((prev) => {
      if (selected) {
        return [...prev, fileId];
      } else {
        return prev.filter((id) => id !== fileId);
      }
    });
  };

  const handleUpload = () => {
    toast.success('Upload feature coming soon');
  };

  const handleNewFolder = () => {
    toast.success('Create folder feature coming soon');
  };

  const handleDelete = () => {
    if (selectedFiles.length === 0) {
      toast.error('Chọn file để xóa');
      return;
    }
    toast.success('Delete feature coming soon');
  };

  const handleRefresh = () => {
    setIsLoading(true);
    setTimeout(() => {
      setIsLoading(false);
      toast.success('Đã làm mới');
    }, 1000);
  };

  const handleFileDelete = () => {
    toast.success('File deleted');
  };

  // Breadcrumb items
  const breadcrumbItems = currentPath
    .split('/')
    .filter(Boolean)
    .map((part, index, arr) => ({
      label: part.charAt(0).toUpperCase() + part.slice(1),
      path: '/' + arr.slice(0, index + 1).join('/'),
    }));

  return (
    <div className="flex h-screen overflow-hidden bg-background">
      {/* Sidebar */}
      <Sidebar
        currentPath={currentPath}
        onPathChange={setCurrentPath}
        isOpen={sidebarOpen}
        onToggle={() => setSidebarOpen(!sidebarOpen)}
      />

      {/* Main content */}
      <div className="flex flex-1 flex-col overflow-hidden">
        {/* Breadcrumb */}
        <Breadcrumb
          items={breadcrumbItems}
          onNavigate={setCurrentPath}
        />

        {/* Toolbar */}
        <Toolbar
          viewMode={viewMode}
          onViewModeChange={setViewMode}
          onUpload={handleUpload}
          onNewFolder={handleNewFolder}
          onDelete={handleDelete}
          onRefresh={handleRefresh}
          onSearchChange={setSearchQuery}
          searchQuery={searchQuery}
          hasSelection={selectedFiles.length > 0}
        />

        {/* File grid/list */}
        <div className="flex-1 overflow-auto bg-background p-4">
          {viewMode === 'grid' ? (
            <FileGrid
              files={processedFiles}
              selectedFiles={selectedFiles}
              onSelect={handleSelectFile}
              onFileClick={setPreviewFile}
              isLoading={isLoading}
            />
          ) : (
            <FileList
              files={processedFiles}
              selectedFiles={selectedFiles}
              onSelect={handleSelectFile}
              onFileClick={setPreviewFile}
              isLoading={isLoading}
            />
          )}
        </div>
      </div>

      {/* Preview modal */}
      <PreviewModal
        file={previewFile}
        onClose={() => setPreviewFile(null)}
        onDelete={handleFileDelete}
      />
    </div>
  );
};
