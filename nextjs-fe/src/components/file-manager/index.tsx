'use client';

import { useState } from 'react';
import { Sidebar } from './sidebar';
import { Breadcrumb } from './breadcrumb';
import { Toolbar } from './toolbar';
import { FileGrid } from './file-grid';
import { FileList } from './file-list';
import { PreviewModal } from './preview-modal';
import { Pagination } from './pagination';
import { useFileManager } from '@/hooks/use-file-manager';
import { buildBreadcrumb } from './utils';
import { UploadDialog } from './dialogs/upload-dialog';
import { NewFolderDialog } from './dialogs/new-folder-dialog';
import { RenameDialog } from './dialogs/rename-dialog';
import { DeleteConfirmDialog } from './dialogs/delete-confirm-dialog';
import { MoveCopyDialog } from './dialogs/move-copy-dialog';
import type { MediaFile } from './types';
import toast from 'react-hot-toast';

export default function FileManager() {
  const {
    currentPath,
    viewMode,
    files,
    selectedFiles,
    isLoading,
    searchQuery,
    filterOptions,
    sortOptions,
    pagination,
    setCurrentPath,
    setViewMode,
    toggleFileSelection,
    selectAllFiles,
    setSearchQuery,
    setFilterOptions,
    setSortOptions,
    setPagination,
    refreshFiles,
    createFolder,
    uploadFiles,
    deleteFiles,
    renameFile,
    moveFiles,
    copyFiles,
  } = useFileManager();

  // Dialog states
  const [isUploadOpen, setIsUploadOpen] = useState(false);
  const [isNewFolderOpen, setIsNewFolderOpen] = useState(false);
  const [isRenameOpen, setIsRenameOpen] = useState(false);
  const [isDeleteOpen, setIsDeleteOpen] = useState(false);
  const [isMoveCopyOpen, setIsMoveCopyOpen] = useState(false);
  const [moveCopyMode, setMoveCopyMode] = useState<'move' | 'copy'>('move');
  
  // Selection state for operations
  const [targetFile, setTargetFile] = useState<MediaFile | null>(null);
  const [previewFile, setPreviewFile] = useState<MediaFile | null>(null);

  const breadcrumbItems = buildBreadcrumb(currentPath);

  // Handlers
  const handleFileClick = (file: MediaFile) => {
    if (file.type === 'folder') {
      setCurrentPath(file.folder_path === '/' ? `/${file.name}` : `${file.folder_path}/${file.name}`);
    } else {
      setPreviewFile(file);
    }
  };

  const handleNavigate = (path: string) => {
    setCurrentPath(path);
  };

  const handleUpload = async (filesToUpload: File[]) => {
    try {
      await uploadFiles(filesToUpload);
      toast.success('Upload thành công');
    } catch (error) {
      toast.error('Upload thất bại');
    }
  };

  const handleCreateFolder = async (name: string) => {
    try {
      await createFolder(name);
      toast.success('Tạo thư mục thành công');
    } catch (error) {
      toast.error('Tạo thư mục thất bại');
    }
  };

  const handleRename = (file: MediaFile) => {
    setTargetFile(file);
    setIsRenameOpen(true);
  };

  const handleRenameSubmit = async (file: MediaFile, newName: string) => {
    try {
      await renameFile(file.id, newName);
      toast.success('Đổi tên thành công');
    } catch (error) {
      toast.error('Đổi tên thất bại');
    }
  };

  const handleDelete = (file?: MediaFile) => {
    if (file) {
      setTargetFile(file);
      // If deleting a single file that isn't in selection, clear selection first
      if (!selectedFiles.includes(file.id)) {
        // Optional: clear selection or just delete this one
        // For simplicity, let's just set targetFile and handle logic in confirm
      }
    } else {
      setTargetFile(null);
    }
    setIsDeleteOpen(true);
  };

  const handleDeleteConfirm = async () => {
    try {
      const idsToDelete = targetFile ? [targetFile.id] : selectedFiles;
      await deleteFiles(idsToDelete);
      toast.success('Xóa thành công');
      setTargetFile(null);
    } catch (error) {
      toast.error('Xóa thất bại');
    }
  };

  const handleMove = (file?: MediaFile) => {
    setMoveCopyMode('move');
    if (file) setTargetFile(file);
    else setTargetFile(null);
    setIsMoveCopyOpen(true);
  };

  const handleCopy = (file?: MediaFile) => {
    setMoveCopyMode('copy');
    if (file) setTargetFile(file);
    else setTargetFile(null);
    setIsMoveCopyOpen(true);
  };

  const handleMoveCopyConfirm = async (targetPath: string) => {
    try {
      const idsToProcess = targetFile ? [targetFile.id] : selectedFiles;
      if (moveCopyMode === 'move') {
        await moveFiles(idsToProcess, targetPath);
        toast.success('Di chuyển thành công');
      } else {
        await copyFiles(idsToProcess, targetPath);
        toast.success('Sao chép thành công');
      }
      setTargetFile(null);
    } catch (error) {
      toast.error(`${moveCopyMode === 'move' ? 'Di chuyển' : 'Sao chép'} thất bại`);
    }
  };

  const handleDownload = (file: MediaFile) => {
    // In a real app, this would trigger a download
    // For mock, we just open the URL
    const link = document.createElement('a');
    link.href = file.url;
    link.download = file.name;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  // Preview Navigation
  const handlePreviewNext = () => {
    if (!previewFile) return;
    const currentIndex = files.findIndex(f => f.id === previewFile.id);
    if (currentIndex < files.length - 1) {
      setPreviewFile(files[currentIndex + 1]);
    }
  };

  const handlePreviewPrev = () => {
    if (!previewFile) return;
    const currentIndex = files.findIndex(f => f.id === previewFile.id);
    if (currentIndex > 0) {
      setPreviewFile(files[currentIndex - 1]);
    }
  };

  const hasNextPreview = previewFile 
    ? files.findIndex(f => f.id === previewFile.id) < files.length - 1 
    : false;
  const hasPrevPreview = previewFile 
    ? files.findIndex(f => f.id === previewFile.id) > 0 
    : false;

  return (
    <div className="flex h-[calc(100vh-4rem)] overflow-hidden bg-background">
      <Sidebar 
        currentPath={currentPath}
        onPathChange={setCurrentPath}
        className="hidden w-64 border-r border-border md:block" 
      />
      
      <div className="flex flex-1 flex-col overflow-hidden">
        <Breadcrumb items={breadcrumbItems} onNavigate={handleNavigate} />
        
        <Toolbar
          viewMode={viewMode}
          onViewModeChange={setViewMode}
          onUpload={() => setIsUploadOpen(true)}
          onNewFolder={() => setIsNewFolderOpen(true)}
          onDelete={() => handleDelete()}
          onMove={() => handleMove()}
          onCopy={() => handleCopy()}
          onRefresh={refreshFiles}
          onSearchChange={setSearchQuery}
          searchQuery={searchQuery}
          selectedCount={selectedFiles.length}
          filterOptions={filterOptions}
          onFilterChange={setFilterOptions}
          sortOptions={sortOptions}
          onSortChange={setSortOptions}
        />

        <div className="flex-1 overflow-auto p-4">
          {viewMode === 'grid' ? (
            <FileGrid
              files={files}
              selectedFiles={selectedFiles}
              onSelect={toggleFileSelection}
              onFileClick={handleFileClick}
              onNavigate={handleNavigate}
              isLoading={isLoading}
              onPreview={setPreviewFile}
              onRename={handleRename}
              onMove={handleMove}
              onCopy={handleCopy}
              onDelete={handleDelete}
              onDownload={handleDownload}
            />
          ) : (
            <FileList
              files={files}
              selectedFiles={selectedFiles}
              onSelect={toggleFileSelection}
              onFileClick={handleFileClick}
              onNavigate={handleNavigate}
              isLoading={isLoading}
              sortField={sortOptions.field}
              sortOrder={sortOptions.order}
              onSort={(field) => setSortOptions({ 
                field, 
                order: sortOptions.field === field && sortOptions.order === 'asc' ? 'desc' : 'asc' 
              })}
              onPreview={setPreviewFile}
              onRename={handleRename}
              onMove={handleMove}
              onCopy={handleCopy}
              onDelete={handleDelete}
              onDownload={handleDownload}
            />
          )}
        </div>
        
        <Pagination 
          pagination={pagination}
          onPageChange={(page) => setPagination({ ...pagination, page })}
        />
      </div>

      {/* Dialogs */}
      <UploadDialog
        open={isUploadOpen}
        onOpenChange={setIsUploadOpen}
        onUpload={handleUpload}
        currentPath={currentPath}
      />

      <NewFolderDialog
        open={isNewFolderOpen}
        onOpenChange={setIsNewFolderOpen}
        onCreateFolder={handleCreateFolder}
      />

      <RenameDialog
        open={isRenameOpen}
        onOpenChange={setIsRenameOpen}
        file={targetFile}
        onRename={handleRenameSubmit}
      />

      <DeleteConfirmDialog
        open={isDeleteOpen}
        onOpenChange={setIsDeleteOpen}
        onConfirm={handleDeleteConfirm}
        count={targetFile ? 1 : selectedFiles.length}
        itemName={targetFile?.name}
      />

      <MoveCopyDialog
        open={isMoveCopyOpen}
        onOpenChange={setIsMoveCopyOpen}
        mode={moveCopyMode}
        count={targetFile ? 1 : selectedFiles.length}
        onConfirm={handleMoveCopyConfirm}
        currentPath={currentPath}
      />

      <PreviewModal
        file={previewFile}
        onClose={() => setPreviewFile(null)}
        onDelete={(file) => handleDelete(file)}
        onNext={handlePreviewNext}
        onPrev={handlePreviewPrev}
        hasNext={hasNextPreview}
        hasPrev={hasPrevPreview}
      />
    </div>
  );
}

// Named export for convenience
export { FileManager };
