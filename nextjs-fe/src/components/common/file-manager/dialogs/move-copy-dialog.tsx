'use client';

import { useState, useEffect } from 'react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { Folder } from 'lucide-react';
import type { Folder as FolderType } from '../types';
import { fileService } from '@/shared/services/modules/file-mock-service';

interface MoveCopyDialogProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  mode: 'move' | 'copy';
  count: number;
  onConfirm: (targetPath: string) => Promise<void>;
  currentPath: string;
}

export const MoveCopyDialog = ({
  open,
  onOpenChange,
  mode,
  count,
  onConfirm,
  currentPath,
}: MoveCopyDialogProps) => {
  const [folders, setFolders] = useState<FolderType[]>([]);
  const [selectedPath, setSelectedPath] = useState<string>('');
  const [isLoading, setIsLoading] = useState(false);
  const [isFetching, setIsFetching] = useState(false);

  useEffect(() => {
    if (open) {
      const fetchFolders = async () => {
        setIsFetching(true);
        try {
          const allFolders = await fileService.getFolders();
          // Filter out current folder and its children (simplified for now)
          // In a real app, we'd need robust logic to prevent moving a folder into itself
          setFolders(allFolders.filter(f => f.path !== currentPath));
          setSelectedPath('');
        } catch (error) {
        } finally {
          setIsFetching(false);
        }
      };
      fetchFolders();
    }
  }, [open, currentPath]);

  const handleConfirm = async () => {
    if (!selectedPath) return;
    
    setIsLoading(true);
    try {
      await onConfirm(selectedPath);
      onOpenChange(false);
    } catch (error) {
    } finally {
      setIsLoading(false);
    }
  };

  const title = mode === 'move' ? 'Di chuyển' : 'Sao chép';
  const action = mode === 'move' ? 'Di chuyển' : 'Sao chép';

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>{title} {count} mục</DialogTitle>
          <DialogDescription>
            Chọn thư mục đích để {action.toLowerCase()} các mục đã chọn.
          </DialogDescription>
        </DialogHeader>
        
        <div className="grid gap-4 py-4">
          <div className="space-y-2">
            <Label>Thư mục đích</Label>
            <Select
              value={selectedPath}
              onValueChange={setSelectedPath}
              disabled={isFetching || isLoading}
            >
              <SelectTrigger>
                <SelectValue placeholder="Chọn thư mục..." />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="/">
                  <div className="flex items-center gap-2">
                    <Folder className="h-4 w-4" />
                    <span>Root (/)</span>
                  </div>
                </SelectItem>
                {folders.map((folder) => (
                  <SelectItem key={folder.id} value={folder.path}>
                    <div className="flex items-center gap-2">
                      <Folder className="h-4 w-4" />
                      <span>{folder.name}</span>
                      <span className="text-xs text-muted-foreground ml-2">
                        {folder.path}
                      </span>
                    </div>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </div>

        <DialogFooter>
          <Button
            variant="outline"
            onClick={() => onOpenChange(false)}
            disabled={isLoading}
          >
            Hủy
          </Button>
          <Button 
            onClick={handleConfirm} 
            disabled={!selectedPath || isLoading}
          >
            {isLoading ? 'Đang xử lý...' : action}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};
