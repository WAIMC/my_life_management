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
import { useTranslations } from 'next-intl';
import type { Folder as FolderType, MoveCopyDialogProps } from '../types';
import { fileService } from '@/shared/services/modules/file-mock-service';

export const MoveCopyDialog = ({
  open,
  onOpenChange,
  mode,
  count,
  onConfirm,
  currentPath,
}: MoveCopyDialogProps) => {
  const t = useTranslations('fileManager.dialogs');
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
          setFolders(allFolders.filter(f => f.path !== currentPath));
          setSelectedPath('');
        } catch {
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
    } catch {
    } finally {
      setIsLoading(false);
    }
  };

  const title = mode === 'move' ? t('moveCopy.moveTitle') : t('moveCopy.copyTitle');
  const action = mode === 'move' ? t('moveCopy.move') : t('moveCopy.copy');

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>{title} {count} {t('moveCopy.items')}</DialogTitle>
          <DialogDescription>
            {t('moveCopy.description', { action: action.toLowerCase() })}
          </DialogDescription>
        </DialogHeader>
        
        <div className="grid gap-4 py-4">
          <div className="space-y-2">
            <Label>{t('moveCopy.targetFolder')}</Label>
            <Select
              value={selectedPath}
              onValueChange={setSelectedPath}
              disabled={isFetching || isLoading}
            >
              <SelectTrigger>
                <SelectValue placeholder={t('moveCopy.selectFolder')} />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="/">
                  <div className="flex items-center gap-2">
                    <Folder className="h-4 w-4" />
                    <span>{t('moveCopy.root')}</span>
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
            {t('cancel')}
          </Button>
          <Button 
            onClick={handleConfirm} 
            disabled={!selectedPath || isLoading}
          >
            {isLoading ? t('processing') : action}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};
