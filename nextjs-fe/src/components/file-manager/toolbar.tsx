'use client';

import {
  Upload,
  FolderPlus,
  Delete,
  RotateCcw,
  Grid3x3,
  List,
  Search,
  MoreVertical,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

interface ToolbarProps {
  viewMode: 'grid' | 'list';
  onViewModeChange: (mode: 'grid' | 'list') => void;
  onUpload: () => void;
  onNewFolder: () => void;
  onDelete: () => void;
  onRefresh: () => void;
  onSearchChange: (query: string) => void;
  searchQuery: string;
  hasSelection: boolean;
}

export const Toolbar = ({
  viewMode,
  onViewModeChange,
  onUpload,
  onNewFolder,
  onDelete,
  onRefresh,
  onSearchChange,
  searchQuery,
  hasSelection,
}: ToolbarProps) => {
  return (
    <div className="flex flex-col gap-4 border-b border-border bg-background px-4 py-4">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-2">
          <Button
            onClick={onUpload}
            variant="default"
            size="sm"
            className="gap-2"
          >
            <Upload className="h-4 w-4" />
            Upload
          </Button>
          <Button
            onClick={onNewFolder}
            variant="outline"
            size="sm"
            className="gap-2"
          >
            <FolderPlus className="h-4 w-4" />
            Thư mục mới
          </Button>
          <Button
            onClick={onDelete}
            variant="outline"
            size="sm"
            className="gap-2"
            disabled={!hasSelection}
          >
            <Delete className="h-4 w-4" />
            Xóa
          </Button>
          <Button
            onClick={onRefresh}
            variant="outline"
            size="sm"
            className="gap-2"
          >
            <RotateCcw className="h-4 w-4" />
            Làm mới
          </Button>
        </div>

        <div className="flex items-center gap-2">
          <Button
            onClick={() => onViewModeChange('grid')}
            variant={viewMode === 'grid' ? 'secondary' : 'ghost'}
            size="sm"
            className="h-8 w-8"
          >
            <Grid3x3 className="h-4 w-4" />
          </Button>
          <Button
            onClick={() => onViewModeChange('list')}
            variant={viewMode === 'list' ? 'secondary' : 'ghost'}
            size="sm"
            className="h-8 w-8"
          >
            <List className="h-4 w-4" />
          </Button>

          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="sm" className="h-8 w-8">
                <MoreVertical className="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem>Sắp xếp theo tên</DropdownMenuItem>
              <DropdownMenuItem>Sắp xếp theo ngày</DropdownMenuItem>
              <DropdownMenuItem>Sắp xếp theo dung lượng</DropdownMenuItem>
              <DropdownMenuItem>Sắp xếp theo loại</DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>

      <div className="relative">
        <Search className="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
        <Input
          placeholder="Tìm kiếm file..."
          value={searchQuery}
          onChange={(e) => onSearchChange(e.target.value)}
          className="pl-10"
        />
      </div>
    </div>
  );
};
