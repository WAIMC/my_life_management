'use client';

import {
  Upload,
  FolderPlus,
  Delete,
  Grid3x3,
  List,
  Search,
  Filter,
  ArrowUpDown,
  CheckSquare,
  Move,
  Copy,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuSeparator,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
} from '@/components/ui/dropdown-menu';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import type { FilterOptions, SortOptions, SortField, SortOrder, FilterType } from './types';

interface ToolbarProps {
  viewMode: 'grid' | 'list';
  onViewModeChange: (mode: 'grid' | 'list') => void;
  onUpload: () => void;
  onNewFolder: () => void;
  onDelete: () => void;
  onMove: () => void;
  onCopy: () => void;
  onSearchChange: (query: string) => void;
  searchQuery: string;
  selectedCount: number;
  filterOptions: FilterOptions;
  onFilterChange: (options: FilterOptions) => void;
  sortOptions: SortOptions;
  onSortChange: (options: SortOptions) => void;
}

export const Toolbar = ({
  viewMode,
  onViewModeChange,
  onUpload,
  onNewFolder,
  onDelete,
  onMove,
  onCopy,
  onSearchChange,
  searchQuery,
  selectedCount,
  filterOptions,
  onFilterChange,
  sortOptions,
  onSortChange,
}: ToolbarProps) => {
  const handleSort = (field: SortField) => {
    if (sortOptions.field === field) {
      onSortChange({
        ...sortOptions,
        order: sortOptions.order === 'asc' ? 'desc' : 'asc',
      });
    } else {
      onSortChange({
        field,
        order: 'asc',
      });
    }
  };

  return (
    <div className="flex flex-col gap-4 border-b border-border bg-background px-4 py-4">
      <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div className="flex flex-wrap items-center gap-2">
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
          
          {selectedCount > 0 && (
            <>
              <div className="mx-2 h-6 w-px bg-border" />
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <CheckSquare className="h-4 w-4" />
                <span>{selectedCount} đã chọn</span>
              </div>
              <Button
                onClick={onMove}
                variant="outline"
                size="sm"
                className="gap-2"
              >
                <Move className="h-4 w-4" />
                Di chuyển
              </Button>
              <Button
                onClick={onCopy}
                variant="outline"
                size="sm"
                className="gap-2"
              >
                <Copy className="h-4 w-4" />
                Sao chép
              </Button>
              <Button
                onClick={onDelete}
                variant="destructive"
                size="sm"
                className="gap-2"
              >
                <Delete className="h-4 w-4" />
                Xóa
              </Button>
            </>
          )}
        </div>

        <div className="flex items-center gap-2">
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="outline" size="sm" className="gap-2">
                <Filter className="h-4 w-4" />
                Lọc: {filterOptions.type === 'all' ? 'Tất cả' : filterOptions.type}
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuRadioGroup
                value={filterOptions.type}
                onValueChange={(value) =>
                  onFilterChange({ ...filterOptions, type: value as FilterType })
                }
              >
                <DropdownMenuRadioItem value="all">Tất cả</DropdownMenuRadioItem>
                <DropdownMenuRadioItem value="images">Hình ảnh</DropdownMenuRadioItem>
                <DropdownMenuRadioItem value="videos">Video</DropdownMenuRadioItem>
                <DropdownMenuRadioItem value="documents">Tài liệu</DropdownMenuRadioItem>
                <DropdownMenuRadioItem value="folders">Thư mục</DropdownMenuRadioItem>
              </DropdownMenuRadioGroup>
            </DropdownMenuContent>
          </DropdownMenu>

          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="outline" size="sm" className="gap-2">
                <ArrowUpDown className="h-4 w-4" />
                Sắp xếp
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem onClick={() => handleSort('name')}>
                Tên {sortOptions.field === 'name' && (sortOptions.order === 'asc' ? '↑' : '↓')}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={() => handleSort('date')}>
                Ngày tạo {sortOptions.field === 'date' && (sortOptions.order === 'asc' ? '↑' : '↓')}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={() => handleSort('size')}>
                Dung lượng {sortOptions.field === 'size' && (sortOptions.order === 'asc' ? '↑' : '↓')}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={() => handleSort('type')}>
                Loại {sortOptions.field === 'type' && (sortOptions.order === 'asc' ? '↑' : '↓')}
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>

          <div className="flex rounded-md border border-input">
            <Button
              onClick={() => onViewModeChange('grid')}
              variant={viewMode === 'grid' ? 'secondary' : 'ghost'}
              size="sm"
              className="h-8 w-8 rounded-r-none px-0"
            >
              <Grid3x3 className="h-4 w-4" />
            </Button>
            <Button
              onClick={() => onViewModeChange('list')}
              variant={viewMode === 'list' ? 'secondary' : 'ghost'}
              size="sm"
              className="h-8 w-8 rounded-l-none px-0"
            >
              <List className="h-4 w-4" />
            </Button>
          </div>
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
