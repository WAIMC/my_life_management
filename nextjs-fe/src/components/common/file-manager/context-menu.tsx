import {
  ContextMenu,
  ContextMenuContent,
  ContextMenuItem,
  ContextMenuSeparator,
  ContextMenuShortcut,
  ContextMenuTrigger,
} from '@/components/ui/context-menu';
import {
  Eye,
  Pencil,
  Move,
  Copy,
  Trash,
  Download,
  FolderInput,
} from 'lucide-react';
import type { MediaFile } from './types';

interface FileContextMenuProps {
  children: React.ReactNode;
  file: MediaFile;
  onPreview: (file: MediaFile) => void;
  onRename: (file: MediaFile) => void;
  onMove: (file: MediaFile) => void;
  onCopy: (file: MediaFile) => void;
  onDelete: (file: MediaFile) => void;
  onDownload?: (file: MediaFile) => void;
}

export const FileContextMenu = ({
  children,
  file,
  onPreview,
  onRename,
  onMove,
  onCopy,
  onDelete,
  onDownload,
}: FileContextMenuProps) => {
  return (
    <ContextMenu>
      <ContextMenuTrigger asChild>{children}</ContextMenuTrigger>
      <ContextMenuContent className="w-64">
        <ContextMenuItem onClick={() => onPreview(file)}>
          <Eye className="mr-2 h-4 w-4" />
          Xem trước
          <ContextMenuShortcut>Enter</ContextMenuShortcut>
        </ContextMenuItem>
        <ContextMenuItem onClick={() => onDownload?.(file)}>
          <Download className="mr-2 h-4 w-4" />
          Tải xuống
        </ContextMenuItem>
        <ContextMenuSeparator />
        <ContextMenuItem onClick={() => onRename(file)}>
          <Pencil className="mr-2 h-4 w-4" />
          Đổi tên
          <ContextMenuShortcut>F2</ContextMenuShortcut>
        </ContextMenuItem>
        <ContextMenuItem onClick={() => onMove(file)}>
          <Move className="mr-2 h-4 w-4" />
          Di chuyển
        </ContextMenuItem>
        <ContextMenuItem onClick={() => onCopy(file)}>
          <Copy className="mr-2 h-4 w-4" />
          Sao chép
        </ContextMenuItem>
        <ContextMenuSeparator />
        <ContextMenuItem
          onClick={() => onDelete(file)}
          className="text-red-600 focus:text-red-600"
        >
          <Trash className="mr-2 h-4 w-4" />
          Xóa
          <ContextMenuShortcut>Del</ContextMenuShortcut>
        </ContextMenuItem>
      </ContextMenuContent>
    </ContextMenu>
  );
};
