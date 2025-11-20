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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { isValidFileName } from '../utils';
import type { MediaFile } from '../types';

interface RenameDialogProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  file: MediaFile | null;
  onRename: (file: MediaFile, newName: string) => Promise<void>;
}

export const RenameDialog = ({
  open,
  onOpenChange,
  file,
  onRename,
}: RenameDialogProps) => {
  const [newName, setNewName] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    if (open && file) {
      setNewName(file.name);
      setError('');
    }
  }, [open, file]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!file) return;
    
    if (!newName.trim()) {
      setError('Tên không được để trống');
      return;
    }

    if (!isValidFileName(newName)) {
      setError('Tên chứa ký tự không hợp lệ');
      return;
    }

    if (newName === file.name) {
      onOpenChange(false);
      return;
    }

    setIsLoading(true);
    try {
      await onRename(file, newName);
      onOpenChange(false);
    } catch (err) {
      setError('Không thể đổi tên');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Đổi tên</DialogTitle>
          <DialogDescription>
            Nhập tên mới cho <span className="font-medium">{file?.name}</span>
          </DialogDescription>
        </DialogHeader>
        <form onSubmit={handleSubmit}>
          <div className="grid gap-4 py-4">
            <div className="grid grid-cols-4 items-center gap-4">
              <Label htmlFor="name" className="text-right">
                Tên mới
              </Label>
              <div className="col-span-3">
                <Input
                  id="name"
                  value={newName}
                  onChange={(e) => {
                    setNewName(e.target.value);
                    setError('');
                  }}
                  autoFocus
                />
                {error && <p className="mt-2 text-sm text-red-500">{error}</p>}
              </div>
            </div>
          </div>
          <DialogFooter>
            <Button
              type="button"
              variant="outline"
              onClick={() => onOpenChange(false)}
              disabled={isLoading}
            >
              Hủy
            </Button>
            <Button type="submit" disabled={isLoading || !newName.trim()}>
              {isLoading ? 'Đang xử lý...' : 'Lưu thay đổi'}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
};
