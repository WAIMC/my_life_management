'use client';

import { X, Copy, Download, Trash2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import type { MediaFile } from './types';
import { formatFileSize } from './utils';
import { format } from 'date-fns';
import toast from 'react-hot-toast';

interface PreviewModalProps {
  file: MediaFile | null;
  onClose: () => void;
  onDelete?: (fileId: string) => void;
}

export const PreviewModal = ({
  file,
  onClose,
  onDelete,
}: PreviewModalProps) => {
  if (!file) return null;

  const handleCopyLink = () => {
    navigator.clipboard.writeText(file.url);
    toast.success('Đã sao chép link');
  };

  const handleDelete = () => {
    if (confirm('Bạn có chắc muốn xóa file này?')) {
      onDelete?.(file.id);
      onClose();
    }
  };

  const isImage = file.mime_type.startsWith('image/');
  const isVideo = file.mime_type.startsWith('video/');
  const isPdf =
    file.mime_type === 'application/pdf' ||
    file.url.includes('.pdf');

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <Card className="relative max-h-[90vh] w-full max-w-4xl overflow-auto">
        {/* Close button */}
        <Button
          onClick={onClose}
          variant="ghost"
          size="sm"
          className="absolute right-4 top-4 z-10"
        >
          <X className="h-4 w-4" />
        </Button>

        <div className="p-6">
          {/* Preview content */}
          {isImage && (
            <div className="mb-6 flex justify-center">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={file.url}
                alt={file.name}
                className="max-h-96 rounded-md object-contain"
              />
            </div>
          )}

          {isVideo && (
            <div className="mb-6 flex justify-center">
              <video
                src={file.url}
                className="max-h-96 rounded-md"
                controls
              />
            </div>
          )}

          {isPdf && (
            <div className="mb-6 flex justify-center">
              <iframe
                src={`${file.url}#toolbar=0`}
                className="h-96 w-full rounded-md"
              />
            </div>
          )}

          {!isImage && !isVideo && !isPdf && (
            <div className="mb-6 flex flex-col items-center justify-center py-12">
              <p className="text-4xl mb-4">📄</p>
              <p className="text-lg font-medium">{file.name}</p>
            </div>
          )}

          {/* File info */}
          <div className="space-y-4 border-t border-border pt-6">
            <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
              <div>
                <p className="text-sm text-muted-foreground">Tên file</p>
                <p className="font-medium">{file.name}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Dung lượng</p>
                <p className="font-medium">{formatFileSize(file.size)}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Loại</p>
                <p className="font-medium">{file.mime_type}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Ngày tạo</p>
                <p className="font-medium">
                  {format(new Date(file.created_at), 'dd/MM/yyyy')}
                </p>
              </div>
            </div>

            {/* Actions */}
            <div className="flex flex-wrap gap-2 border-t border-border pt-6">
              <Button
                onClick={handleCopyLink}
                variant="outline"
                size="sm"
                className="gap-2"
              >
                <Copy className="h-4 w-4" />
                Sao chép link
              </Button>
              <a
                href={file.url}
                download
                className="inline-flex items-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-2"
              >
                <Download className="h-4 w-4" />
                Tải xuống
              </a>
              <Button
                onClick={handleDelete}
                variant="destructive"
                size="sm"
                className="gap-2"
              >
                <Trash2 className="h-4 w-4" />
                Xóa
              </Button>
            </div>
          </div>
        </div>
      </Card>
    </div>
  );
};
