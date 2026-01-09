'use client';

import { useState, useRef, useCallback } from 'react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { Upload, X, File, AlertCircle } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { cn } from "@/shared/utils";
import type { UploadDialogProps } from '../types';
import { formatFileSize } from '../utils';

const UPLOAD_PROGRESS_UPDATE_INTERVAL = 200;
const UPLOAD_PROGRESS_MAX_BEFORE_COMPLETION = 90;
const UPLOAD_PROGRESS_INCREMENT = 10;
const UPLOAD_SUCCESS_DELAY = 500;

export const UploadDialog = ({
  open,
  onOpenChange,
  onUpload,
  currentPath,
}: UploadDialogProps) => {
  const t = useTranslations('fileManager.dialogs');
  const [isDragging, setIsDragging] = useState(false);
  const [files, setFiles] = useState<File[]>([]);
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);
  const [error, setError] = useState<string | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleDragOver = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(true);
  }, []);

  const handleDragLeave = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
  }, []);

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
    if (e.dataTransfer.files?.length) {
      setFiles((prev) => [...prev, ...Array.from(e.dataTransfer.files)]);
    }
  }, []);

  const handleFileSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files?.length) {
      setFiles((prev) => [...prev, ...Array.from(e.target.files!)]);
    }
    // Reset input so same files can be selected again if needed
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  const removeFile = (index: number) => {
    setFiles((prev) => prev.filter((_, i) => i !== index));
  };

  const handleUpload = async () => {
    if (files.length === 0) return;

    setUploading(true);
    setProgress(0);
    setError(null);

    // Simulate progress
    const interval = setInterval(() => {
      setProgress((prev) => {
        if (prev >= UPLOAD_PROGRESS_MAX_BEFORE_COMPLETION) return prev;
        return prev + UPLOAD_PROGRESS_INCREMENT;
      });
    }, UPLOAD_PROGRESS_UPDATE_INTERVAL);

    try {
      await onUpload(files);
      setProgress(100);
      setTimeout(() => {
        setFiles([]);
        setUploading(false);
        setProgress(0);
        onOpenChange(false);
      }, UPLOAD_SUCCESS_DELAY);
    } catch (err: unknown) {
      setUploading(false);
      setProgress(0);
      
      // Extract error message from API response
      let errorMessage = t('upload.uploadError');
      
      if (typeof err === 'object' && err !== null) {
        const error = err as Record<string, unknown>;
        if (error.response && typeof error.response === 'object' && error.response !== null) {
          const response = error.response as Record<string, unknown>;
          if (response.data && typeof response.data === 'object' && response.data !== null) {
            const data = response.data as Record<string, unknown>;
            if (data.error && typeof data.error === 'object' && data.error !== null) {
              const errorObj = data.error as Record<string, unknown>;
              if (typeof errorObj.messages === 'string') {
                errorMessage = errorObj.messages;
              }
            }
          }
        } else if (typeof error.message === 'string') {
          errorMessage = error.message;
        }
      }
      
      setError(errorMessage);
      console.error('Upload error:', err);
    } finally {
      clearInterval(interval);
    }
  };

  return (
    <Dialog open={open} onOpenChange={(val) => !uploading && onOpenChange(val)}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>{t('upload.uploadFiles')}</DialogTitle>
          <DialogDescription>
            {t('upload.uploadTo', { path: currentPath })}
          </DialogDescription>
        </DialogHeader>

        <div className="space-y-4">
          {/* Drop Zone */}
          <div
            className={cn(
              'relative flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-muted-foreground/25 px-6 py-10 text-center transition-colors hover:bg-muted/50',
              isDragging && 'border-primary bg-primary/5',
              uploading && 'pointer-events-none opacity-50'
            )}
            onDragOver={handleDragOver}
            onDragLeave={handleDragLeave}
            onDrop={handleDrop}
            onClick={() => fileInputRef.current?.click()}
          >
            <input
              ref={fileInputRef}
              type="file"
              multiple
              className="hidden"
              onChange={handleFileSelect}
              disabled={uploading}
            />
            <div className="flex flex-col items-center gap-2 cursor-pointer">
              <div className="rounded-full bg-primary/10 p-4">
                <Upload className="h-6 w-6 text-primary" />
              </div>
              <div className="text-sm">
                <span className="font-semibold text-primary">{t('upload.clickToSelect')}</span> {t('upload.orDragDrop')}
              </div>
              <p className="text-xs text-muted-foreground">
                {t('upload.supportedFiles')}
              </p>
            </div>
          </div>

          {/* File List */}
          {files.length > 0 && (
            <div className="max-h-[200px] overflow-y-auto space-y-2">
              {files.map((file, index) => (
                <div
                  key={`${file.name}-${index}`}
                  className="flex items-center justify-between rounded-md border border-border p-2 text-sm"
                >
                  <div className="flex items-center gap-2 overflow-hidden">
                    <File className="h-4 w-4 flex-shrink-0 text-muted-foreground" />
                    <span className="truncate">{file.name}</span>
                    <span className="text-xs text-muted-foreground">
                      ({formatFileSize(file.size)})
                    </span>
                  </div>
                  {!uploading && (
                    <Button
                      variant="ghost"
                      size="icon"
                      className="h-6 w-6"
                      onClick={() => removeFile(index)}
                    >
                      <X className="h-4 w-4" />
                    </Button>
                  )}
                </div>
              ))}
            </div>
          )}

          {/* Error Message */}
          {error && (
            <div className="flex items-start gap-2 rounded-md border border-destructive/50 bg-destructive/10 p-3 text-sm">
              <AlertCircle className="h-4 w-4 flex-shrink-0 text-destructive mt-0.5" />
              <div className="flex-1">
                <p className="font-medium text-destructive">{t('upload.uploadFailed')}</p>
                <p className="text-destructive/90 mt-1">{error}</p>
              </div>
              <Button
                variant="ghost"
                size="icon"
                className="h-6 w-6"
                onClick={() => setError(null)}
              >
                <X className="h-4 w-4" />
              </Button>
            </div>
          )}

          {/* Progress Bar */}
          {uploading && (
            <div className="space-y-2">
              <div className="flex justify-between text-xs">
                <span>{t('upload.uploading')}</span>
                <span>{progress}%</span>
              </div>
              <Progress value={progress} className="h-2" />
            </div>
          )}

          {/* Actions */}
          <div className="flex justify-end gap-2">
            <Button
              variant="outline"
              onClick={() => onOpenChange(false)}
              disabled={uploading}
            >
              {t('cancel')}
            </Button>
            <Button
              onClick={handleUpload}
              disabled={files.length === 0 || uploading}
            >
              {uploading ? t('upload.uploading') : t('upload.uploadButton')}
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
};
