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

interface NewFolderDialogProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  onCreateFolder: (name: string) => Promise<void>;
}

export const NewFolderDialog = ({
  open,
  onOpenChange,
  onCreateFolder,
}: NewFolderDialogProps) => {
  const [folderName, setFolderName] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    if (open) {
      setFolderName('');
      setError('');
    }
  }, [open]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!folderName.trim()) {
      setError('Tên thư mục không được để trống');
      return;
    }

    if (!isValidFileName(folderName)) {
      setError('Tên thư mục chứa ký tự không hợp lệ');
      return;
    }

    setIsLoading(true);
    try {
      await onCreateFolder(folderName);
      onOpenChange(false);
    } catch (err) {
      setError('Không thể tạo thư mục');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Tạo thư mục mới</DialogTitle>
          <DialogDescription>
            Nhập tên cho thư mục mới.
          </DialogDescription>
        </DialogHeader>
        <form onSubmit={handleSubmit}>
          <div className="grid gap-4 py-4">
            <div className="grid grid-cols-4 items-center gap-4">
              <Label htmlFor="name" className="text-right">
                Tên
              </Label>
              <div className="col-span-3">
                <Input
                  id="name"
                  value={folderName}
                  onChange={(e) => {
                    setFolderName(e.target.value);
                    setError('');
                  }}
                  placeholder="Thư mục mới"
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
            <Button type="submit" disabled={isLoading || !folderName.trim()}>
              {isLoading ? 'Đang tạo...' : 'Tạo thư mục'}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
};
