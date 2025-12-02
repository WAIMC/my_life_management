'use client';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { MoreHorizontal, ArrowUpDown, Folder } from 'lucide-react';
import type { MediaFile, SortField, SortOrder } from './types';
import { formatFileSize, getFileIcon, getMimeTypeLabel } from './utils';
import { format } from 'date-fns';
import { FileContextMenu } from './context-menu';

interface FileListProps {
  files: MediaFile[];
  selectedFiles: string[];
  onSelect: (fileId: string, selected: boolean) => void;
  onSelectAll: (selected: boolean) => void;
  onFileClick: (file: MediaFile) => void;
  onNavigate: (path: string) => void;
  isLoading?: boolean;
  sortField?: SortField;
  sortOrder?: SortOrder;
  onSort?: (field: SortField) => void;
  onPreview: (file: MediaFile) => void;
  onRename: (file: MediaFile) => void;
  onMove: (file: MediaFile) => void;
  onCopy: (file: MediaFile) => void;
  onDelete: (file: MediaFile) => void;
  onDownload?: (file: MediaFile) => void;
}

export const FileList = ({
  files,
  selectedFiles,
  onSelect,
  onSelectAll,
  onFileClick,
  onNavigate,
  isLoading = false,
  sortField,
  sortOrder,
  onSort,
  onPreview,
  onRename,
  onMove,
  onCopy,
  onDelete,
  onDownload,
}: FileListProps) => {
  if (isLoading) {
    return <div className="py-8 text-center text-muted-foreground">Đang tải...</div>;
  }

  if (files.length === 0) {
    return (
      <div className="py-8 text-center text-muted-foreground">
        Không có file nào
      </div>
    );
  }

  const handleSelectAll = (checked: boolean) => {
    onSelectAll(checked);
  };

  const handleDoubleClick = (file: MediaFile) => {
    if (file.type === 'folder') {
      onNavigate(file.folder_path === '/' ? `/${file.name}` : `${file.folder_path}/${file.name}`);
    } else {
      onPreview(file);
    }
  };

  const renderSortIcon = (field: SortField) => {
    if (sortField !== field) return <ArrowUpDown className="ml-2 h-4 w-4 opacity-50" />;
    return <ArrowUpDown className={`ml-2 h-4 w-4 ${sortOrder === 'desc' ? 'rotate-180' : ''}`} />;
  };

  return (
    <div className="rounded-md border border-border bg-background">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead className="w-12">
              <Checkbox
                checked={
                  files.length > 0 &&
                  selectedFiles.length === files.length
                }
                onCheckedChange={(checked: boolean | 'indeterminate') => {
                  if (checked === 'indeterminate') return;
                  handleSelectAll(checked === true);
                }}
              />
            </TableHead>
            <TableHead 
              className="cursor-pointer hover:bg-muted/50"
              onClick={() => onSort?.('name')}
            >
              <div className="flex items-center">
                Tên {renderSortIcon('name')}
              </div>
            </TableHead>
            <TableHead 
              className="cursor-pointer hover:bg-muted/50"
              onClick={() => onSort?.('type')}
            >
              <div className="flex items-center">
                Loại {renderSortIcon('type')}
              </div>
            </TableHead>
            <TableHead 
              className="text-right cursor-pointer hover:bg-muted/50"
              onClick={() => onSort?.('size')}
            >
              <div className="flex items-center justify-end">
                Dung lượng {renderSortIcon('size')}
              </div>
            </TableHead>
            <TableHead 
              className="cursor-pointer hover:bg-muted/50"
              onClick={() => onSort?.('date')}
            >
              <div className="flex items-center">
                Ngày tạo {renderSortIcon('date')}
              </div>
            </TableHead>
            <TableHead className="w-12"></TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
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
                <TableRow
                  className={`cursor-pointer hover:bg-accent/50 ${isSelected ? 'bg-accent' : ''}`}
                  onClick={() => onFileClick(file)}
                  onDoubleClick={() => handleDoubleClick(file)}
                >
                  <TableCell onClick={(e) => e.stopPropagation()}>
                    <Checkbox
                      checked={isSelected}
                      onCheckedChange={(checked: boolean | 'indeterminate') => {
                        onSelect(file.id, checked === true);
                      }}
                    />
                  </TableCell>
                  <TableCell className="font-medium">
                    <div className="flex items-center gap-3">
                      {file.type === 'folder' ? (
                        <Folder className="h-5 w-5 text-blue-500" fill="currentColor" />
                      ) : (
                        <Icon className="h-5 w-5 text-muted-foreground" />
                      )}
                      <span className="truncate max-w-[200px] md:max-w-[300px]" title={file.name}>
                        {file.name}
                      </span>
                    </div>
                  </TableCell>
                  <TableCell className="text-muted-foreground">
                    {getMimeTypeLabel(file.mime_type)}
                  </TableCell>
                  <TableCell className="text-right text-muted-foreground">
                    {formatFileSize(file.size)}
                  </TableCell>
                  <TableCell className="text-muted-foreground">
                    {format(new Date(file.created_at), 'dd/MM/yyyy HH:mm')}
                  </TableCell>
                  <TableCell onClick={(e) => e.stopPropagation()}>
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="h-8 w-8 p-0">
                          <span className="sr-only">Open menu</span>
                          <MoreHorizontal className="h-4 w-4" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem onClick={() => onPreview(file)}>
                          Xem trước
                        </DropdownMenuItem>
                        <DropdownMenuItem onClick={() => onDownload?.(file)}>
                          Tải xuống
                        </DropdownMenuItem>
                        <DropdownMenuItem onClick={() => onRename(file)}>
                          Đổi tên
                        </DropdownMenuItem>
                        <DropdownMenuItem onClick={() => onMove(file)}>
                          Di chuyển
                        </DropdownMenuItem>
                        <DropdownMenuItem onClick={() => onCopy(file)}>
                          Sao chép
                        </DropdownMenuItem>
                        <DropdownMenuItem 
                          onClick={() => onDelete(file)}
                          className="text-red-600 focus:text-red-600"
                        >
                          Xóa
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </TableCell>
                </TableRow>
              </FileContextMenu>
            );
          })}
        </TableBody>
      </Table>
    </div>
  );
};
