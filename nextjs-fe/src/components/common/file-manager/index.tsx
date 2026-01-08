'use client';

import { useState } from 'react';
import { Sidebar } from './sidebar';
import { Breadcrumb } from './breadcrumb';
import { Toolbar } from './toolbar';
import { FileGrid } from './file-grid';
import { FileList } from './file-list';
import { PreviewModal } from './preview-modal';
import { Pagination } from './pagination';
import { useFileManager } from '@/shared/hooks/use-file-manager';
import { buildBreadcrumb } from './utils';
import { UploadDialog } from './dialogs/upload-dialog';
import { NewFolderDialog } from './dialogs/new-folder-dialog';
import { RenameDialog } from './dialogs/rename-dialog';
import { DeleteConfirmDialog } from './dialogs/delete-confirm-dialog';
import { MoveCopyDialog } from './dialogs/move-copy-dialog';
import type { MediaFile } from './types';
import toast from 'react-hot-toast';
import { useTranslations } from 'next-intl';

export default function FileManager() {
  const t = useTranslations();
  const {
    currentPath,
    viewMode,
    files,
    selectedFiles,
    isLoading,
    searchQuery,

    filterType,
    sortBy,
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
      if (uploadFiles) {
        await uploadFiles(filesToUpload);
        toast.success(t('fileManager.uploadSuccess'));
      } else {
        toast.error(t('fileManager.featureUnavailable'));
      }
    } catch (error) {
      toast.error(t('fileManager.uploadFailed'));
    }
  };

  const handleCreateFolder = async (name: string) => {
    try {
      if (createFolder) {
        await createFolder(name);
        toast.success(t('fileManager.createFolderSuccess'));
      } else {
        toast.error(t('fileManager.featureUnavailable'));
      }
    } catch (error) {
      toast.error(t('fileManager.createFolderFailed'));
    }
  };

  const handleRename = (file: MediaFile) => {
    setTargetFile(file);
    setIsRenameOpen(true);
  };

  const handleRenameSubmit = async (file: MediaFile, newName: string) => {
    try {
      if (renameFile) {
        await renameFile(file.id, newName);
        toast.success(t('fileManager.renameSuccess'));
      } else {
        toast.error(t('fileManager.featureUnavailable'));
      }
    } catch (error) {
      toast.error(t('fileManager.renameFailed'));
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
      if (deleteFiles) {
        await deleteFiles(idsToDelete);
        toast.success(t('fileManager.deleteSuccess'));
      } else {
        toast.error(t('fileManager.featureUnavailable'));
      }
      setTargetFile(null);
    } catch (error) {
      toast.error(t('fileManager.deleteFailed'));
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
        if (moveFiles) {
          await moveFiles(idsToProcess, targetPath);
          toast.success(t('fileManager.moveSuccess'));
        } else {
          toast.error(t('fileManager.featureUnavailable'));
        }
      } else {
        if (copyFiles) {
          await copyFiles(idsToProcess, targetPath);
          toast.success(t('fileManager.copySuccess'));
        } else {
          toast.error(t('fileManager.featureUnavailable'));
        }
      }
      setTargetFile(null);
    } catch (error) {
      const errorKey = moveCopyMode === 'move' ? 'fileManager.moveFailed' : 'fileManager.copyFailed';
      toast.error(t(errorKey));
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

          onSearchChange={setSearchQuery}
          searchQuery={searchQuery}
          selectedCount={selectedFiles.length}
          filterOptions={{ type: (filterType as any) || 'all' }}
          onFilterChange={(opts) => setFilterOptions?.(opts)}
          sortOptions={{ field: (sortBy as any) || 'name', order: 'asc' }}
          onSortChange={(opts) => setSortOptions?.(opts)}
        />

        <div className="flex-1 overflow-auto p-4">
          {viewMode === 'grid' ? (
            <FileGrid
              files={files}
              selectedFiles={selectedFiles}
              onSelect={(id, val) => toggleFileSelection?.(id, val)}
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
              onSelect={(id, val) => toggleFileSelection?.(id, val)}
              onSelectAll={(val) => selectAllFiles?.(val)}
              onFileClick={handleFileClick}
              onNavigate={handleNavigate}
              isLoading={isLoading}
              sortField={(sortBy as any) || 'name'}
              sortOrder="asc"
              onSort={(field) => setSortOptions?.({
                field: field as any,
                order: 'asc'
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
          pagination={pagination || { page: 1, pageSize: 20, total: 0, totalPages: 1 }}
          onPageChange={(page) => setPagination?.({ ...(pagination || { page: 1, pageSize: 20, total: 0, totalPages: 1 }), page })}
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

// Named exports for convenience
export { FileManager };
export { FileManagerContent } from './file-manager-content';
