'use client';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Checkbox } from '@/components/ui/checkbox';
import type { MediaFile } from './types';
import { formatFileSize, getFileIcon } from './utils';
import { format } from 'date-fns';

interface FileListProps {
  files: MediaFile[];
  selectedFiles: string[];
  onSelect: (fileId: string, selected: boolean) => void;
  onFileClick: (file: MediaFile) => void;
  isLoading?: boolean;
}

export const FileList = ({
  files,
  selectedFiles,
  onSelect,
  onFileClick,
  isLoading = false,
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

  return (
    <div className="rounded-md border border-border">
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
                  files.forEach((file) => {
                    onSelect(file.id, checked === true);
                  });
                }}
              />
            </TableHead>
            <TableHead>Tên</TableHead>
            <TableHead>Loại</TableHead>
            <TableHead className="text-right">Dung lượng</TableHead>
            <TableHead>Ngày tạo</TableHead>
            <TableHead className="text-right">Tác vụ</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {files.map((file) => {
            const isSelected = selectedFiles.includes(file.id);
            const Icon = getFileIcon(file.mime_type);

            return (
              <TableRow
                key={file.id}
                className="cursor-pointer hover:bg-accent"
                onClick={() => onFileClick(file)}
              >
                <TableCell>
                  <Checkbox
                    checked={isSelected}
                    onCheckedChange={(checked: boolean | 'indeterminate') => {
                      onSelect(file.id, checked === true);
                    }}
                    onClick={(e: React.MouseEvent) => e.stopPropagation()}
                  />
                </TableCell>
                <TableCell className="font-medium">
                  <div className="flex items-center gap-2">
                    {file.type === 'folder' ? (
                      <span className="text-lg">📁</span>
                    ) : (
                      <Icon className="h-4 w-4 text-muted-foreground" />
                    )}
                    <span className="truncate">{file.name}</span>
                  </div>
                </TableCell>
                <TableCell>{file.mime_type}</TableCell>
                <TableCell className="text-right">
                  {formatFileSize(file.size)}
                </TableCell>
                <TableCell>
                  {format(new Date(file.created_at), 'dd/MM/yyyy HH:mm')}
                </TableCell>
                <TableCell className="text-right">
                  <div className="flex items-center justify-end gap-2">
                    {/* Action buttons will go here */}
                  </div>
                </TableCell>
              </TableRow>
            );
          })}
        </TableBody>
      </Table>
    </div>
  );
};
