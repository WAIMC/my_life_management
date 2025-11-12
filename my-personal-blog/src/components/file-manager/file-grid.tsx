'use client';

import { Card } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import type { MediaFile } from './types';
import { formatFileSize, getFileIcon } from '@/lib/utils';
import { format } from 'date-fns';

interface FileGridProps {
  files: MediaFile[];
  selectedFiles: string[];
  onSelect: (fileId: string, selected: boolean) => void;
  onFileClick: (file: MediaFile) => void;
  isLoading?: boolean;
}

export const FileGrid = ({
  files,
  selectedFiles,
  onSelect,
  onFileClick,
  isLoading = false,
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

  return (
    <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
      {files.map((file) => {
        const isSelected = selectedFiles.includes(file.id);
        const Icon = getFileIcon(file.mime_type);

        return (
          <Card
            key={file.id}
            className="group relative overflow-hidden transition-all hover:shadow-lg hover:shadow-primary/20 cursor-pointer"
            onClick={() => onFileClick(file)}
          >
            {/* Checkbox */}
            <div className="absolute right-2 top-2 z-10 flex items-center gap-2">
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
                <div className="flex h-full items-center justify-center">
                  <span className="text-4xl">📁</span>
                </div>
              ) : file.mime_type.startsWith('image/') ? (
                // eslint-disable-next-line @next/next/no-img-element
                <img
                  src={file.url}
                  alt={file.name}
                  className="h-full w-full object-cover"
                  onError={(e) => {
                    (e.target as HTMLImageElement).src =
                      'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23ddd" width="100" height="100"/%3E%3C/svg%3E';
                  }}
                />
              ) : (
                <div className="flex h-full items-center justify-center">
                  <Icon className="h-12 w-12 text-muted-foreground" />
                </div>
              )}
            </div>

            {/* File Info */}
            <div className="space-y-2 p-3">
              <p className="truncate text-sm font-medium text-foreground">
                {file.name}
              </p>
              <div className="flex items-center justify-between">
                <span className="text-xs text-muted-foreground">
                  {formatFileSize(file.size)}
                </span>
                <span className="text-xs text-muted-foreground">
                  {format(new Date(file.created_at), 'dd/MM/yy')}
                </span>
              </div>
            </div>
          </Card>
        );
      })}
    </div>
  );
};
