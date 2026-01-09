'use client';

import { useState, useCallback, useRef } from 'react';
import { apiClient } from '@/shared/api/client';
import { ENDPOINTS } from '@/shared/api';
import { notification } from '@/shared/utils/notification';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { Upload, X, Image as ImageIcon } from 'lucide-react';
import Image from 'next/image';
import type { UploadResponse } from '@/shared/types/api';
import type { FileUploadProps } from '@/shared/types/upload.types';
import { useTranslations } from 'next-intl';

export function FileUpload({
  value,
  onChange,
  accept = 'image/*',
  maxSize = 5,
  className = '',
  disabled = false,
}: FileUploadProps) {
  const t = useTranslations();
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);
  const [dragActive, setDragActive] = useState(false);
  const [preview, setPreview] = useState<string | null>(value || null);
  const inputRef = useRef<HTMLInputElement>(null);

  const validateFile = useCallback((file: File): boolean => {
    // Check file type
    if (accept && !file.type.match(accept.replace('*', '.*'))) {
      notification.error(t('media.invalidFileType', { accept }));
      return false;
    }

    // Check file size
    const fileSizeMB = file.size / (1024 * 1024);
    if (fileSizeMB > maxSize) {
      notification.error(t('media.fileSizeExceeded', { maxSize }));
      return false;
    }

    return true;
  }, [accept, maxSize, t]);

  const uploadFile = useCallback(async (file: File) => {
    if (!validateFile(file)) return;

    try {
      setUploading(true);
      setProgress(0);

      const formData = new FormData();
      formData.append('file', file);

      // Simulate progress (since we don't have real upload progress from API)
      const progressInterval = setInterval(() => {
        setProgress((prev) => {
          if (prev >= 90) {
            clearInterval(progressInterval);
            return 90;
          }
          return prev + 10;
        });
      }, 100);

      const response = await apiClient.post<UploadResponse>(ENDPOINTS.MEDIA.UPLOAD, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      clearInterval(progressInterval);
      setProgress(100);

      const uploadedUrl = response.data.url;
      setPreview(uploadedUrl);
      onChange(uploadedUrl);
      notification.success(t('media.fileUploadedSuccessfully'));
    } catch (error: unknown) {
      const message = error instanceof Error && 'response' in error 
        ? (error as { response?: { data?: { message?: string } } }).response?.data?.message || t('media.failedToUploadFile')
        : t('media.failedToUploadFile');
      notification.error(message);
    } finally {
      setUploading(false);
      setProgress(0);
    }
  }, [validateFile, onChange, t]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      uploadFile(file);
    }
  };

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === 'dragenter' || e.type === 'dragover') {
      setDragActive(true);
    } else if (e.type === 'dragleave') {
      setDragActive(false);
    }
  }, []);

  const handleDrop = useCallback(
    (e: React.DragEvent) => {
      e.preventDefault();
      e.stopPropagation();
      setDragActive(false);

      if (disabled || uploading) return;

      const file = e.dataTransfer.files?.[0];
      if (file) {
        uploadFile(file);
      }
    },
    [disabled, uploading, uploadFile]
  );

  const handleRemove = () => {
    setPreview(null);
    onChange('');
    if (inputRef.current) {
      inputRef.current.value = '';
    }
  };

  const handleClick = () => {
    if (!disabled && !uploading) {
      inputRef.current?.click();
    }
  };

  return (
    <div className={`space-y-4 ${className}`}>
      {/* Upload Area */}
      {!preview && (
        <div
          className={`
            relative border-2 border-dashed rounded-lg p-8 text-center cursor-pointer
            transition-colors duration-200
            ${dragActive ? 'border-primary bg-primary/5' : 'border-gray-300 dark:border-gray-700'}
            ${disabled ? 'opacity-50 cursor-not-allowed' : 'hover:border-primary hover:bg-primary/5'}
          `}
          onDragEnter={handleDrag}
          onDragLeave={handleDrag}
          onDragOver={handleDrag}
          onDrop={handleDrop}
          onClick={handleClick}
        >
          <input
            ref={inputRef}
            type="file"
            accept={accept}
            onChange={handleFileChange}
            disabled={disabled || uploading}
            className="hidden"
          />

          <div className="flex flex-col items-center gap-2">
            <Upload className="h-10 w-10 text-gray-400" />
            <div className="text-sm text-gray-600 dark:text-gray-400">
              <span className="font-semibold text-primary">{t('media.clickToUpload')}</span> {t('media.orDragAndDrop')}
            </div>
            <div className="text-xs text-gray-500">
              {accept} ({t('media.maxFileSize', { maxSize })})
            </div>
          </div>
        </div>
      )}

      {/* Upload Progress */}
      {uploading && (
        <div className="space-y-2">
          <div className="flex items-center justify-between text-sm">
            <span className="text-gray-600 dark:text-gray-400">{t('media.uploading')}</span>
            <span className="text-gray-600 dark:text-gray-400">{progress}%</span>
          </div>
          <Progress value={progress} className="h-2" />
        </div>
      )}

      {/* Image Preview */}
      {preview && !uploading && (
        <div className="relative group">
          <div className="relative aspect-video w-full max-w-md overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
            {accept.includes('image') ? (
              <Image
                src={preview}
                alt="Preview"
                fill
                className="object-cover"
                unoptimized
              />
            ) : (
              <div className="flex h-full items-center justify-center bg-gray-100 dark:bg-gray-800">
                <ImageIcon className="h-16 w-16 text-gray-400" />
              </div>
            )}
          </div>

          {/* Remove Button */}
          <Button
            type="button"
            variant="destructive"
            size="icon"
            className="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity"
            onClick={handleRemove}
            disabled={disabled}
          >
            <X className="h-4 w-4" />
          </Button>

          {/* Change Button */}
          <Button
            type="button"
            variant="outline"
            size="sm"
            className="mt-2"
            onClick={handleClick}
            disabled={disabled}
          >
            {t('media.changeImage')}
          </Button>
        </div>
      )}
    </div>
  );
}
