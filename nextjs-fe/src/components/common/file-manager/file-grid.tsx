'use client';

import { Card } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import type { MediaFile } from './types';
import { formatFileSize, getFileIcon } from './utils';
import { format } from 'date-fns';
import { FileContextMenu } from './context-menu';
import { Folder } from 'lucide-react';

interface FileGridProps {
  files: MediaFile[];
  selectedFiles: string[];
  onSelect: (fileId: string, selected: boolean) => void;
  onFileClick: (file: MediaFile) => void;
  onNavigate: (path: string) => void;
  isLoading?: boolean;
  onPreview: (file: MediaFile) => void;
  onRename: (file: MediaFile) => void;
  onMove: (file: MediaFile) => void;
  onCopy: (file: MediaFile) => void;
  onDelete: (file: MediaFile) => void;
  onDownload?: (file: MediaFile) => void;
}

export const FileGrid = ({
  files,
  selectedFiles,
  onSelect,
  onFileClick,
  onNavigate,
  isLoading = false,
  onPreview,
  onRename,
  onMove,
  onCopy,
  onDelete,
  onDownload,
}: FileGridProps) => {
  if (isLoading) {
    return (
      <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        {Array.from({ length: 10 }).map((_, i) => (
          <Card key={i} className="h-40 animate-pulse bg-muted" />
        ))}
      </div>
    );
  }

  if (files.length === 0) {
    return (
      <div className="flex flex-col items-center justify-center py-12">
        <p className="text-lg text-muted-foreground">Không có file nào</p>
      </div>
    );
  }

  const handleDoubleClick = (file: MediaFile) => {
    if (file.type === 'folder') {
      onNavigate(file.folder_path === '/' ? `/${file.name}` : `${file.folder_path}/${file.name}`);
    } else {
      onPreview(file);
    }
  };

  return (
    <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 pb-20">
      {files.map((file) => {
        const isSelected = selectedFiles.includes(file.id);
        const Icon = getFileIcon(file.mime_type);

        return (
          <FileContextMenu
            key={file.id}
            file={file}
            onPreview={onPreview}
            onRename={onRename}
            onMove={onMove}
            onCopy={onCopy}
            onDelete={onDelete}
            onDownload={onDownload}
          >
            <Card
              className={`group relative overflow-hidden transition-all hover:shadow-lg cursor-pointer ${
                isSelected ? 'ring-2 ring-primary shadow-md' : 'hover:shadow-primary/20'
              }`}
              onClick={() => onFileClick(file)}
              onDoubleClick={() => handleDoubleClick(file)}
            >
              {/* Checkbox */}
              <div className={`absolute right-2 top-2 z-10 flex items-center gap-2 transition-opacity ${isSelected ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'}`}>
                <Checkbox
                  checked={isSelected}
                  onCheckedChange={(checked: boolean | 'indeterminate') => {
                    onSelect(file.id, checked === true);
                  }}
                  onClick={(e: React.MouseEvent) => e.stopPropagation()}
                />
              </div>

              {/* Preview/Icon Area */}
              <div className="relative h-32 w-full overflow-hidden bg-muted">
                {file.type === 'folder' ? (
                  <div className="flex h-full items-center justify-center bg-blue-50 dark:bg-blue-900/20">
                    <Folder className="h-16 w-16 text-blue-500" fill="currentColor" />
                  </div>
                ) : file.mime_type?.startsWith('image/') ? (
                  // eslint-disable-next-line @next/next/no-img-element
                  <img
                    src={file.url}
                    alt={file.name}
                    className="h-full w-full object-cover"
                    loading="lazy"
                  />
                ) : (
                  <div className="flex h-full items-center justify-center">
                    <Icon className="h-12 w-12 text-muted-foreground" />
                  </div>
                )}
              </div>

              {/* File Info */}
              <div className="space-y-1 p-3">
                <p className="truncate text-sm font-medium text-foreground" title={file.name}>
                  {file.name}
                </p>
                <div className="flex items-center justify-between text-xs text-muted-foreground">
                  <span>{formatFileSize(file.size)}</span>
                  <span>{format(new Date(file.created_at), 'dd/MM/yy')}</span>
                </div>
              </div>
            </Card>
          </FileContextMenu>
        );
      })}
    </div>
  );
};
