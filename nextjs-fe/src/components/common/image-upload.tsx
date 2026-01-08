'use client';

import { useState, useRef, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Upload, X, Image as ImageIcon } from 'lucide-react';
import { cn } from "@/shared/utils";
import toast from 'react-hot-toast';
import { useTranslations } from 'next-intl';

interface ImageUploadProps {
  value?: string; // Current image URL
  onChange: (file: File | null, previewUrl: string | null) => void;
  maxSize?: number; // in MB
  className?: string;
  label?: string;
  shape?: 'square' | 'rectangle' | 'circle';
  aspectRatio?: string; // e.g., 'aspect-video' or custom class
}

export function ImageUpload({
  value,
  onChange,
  maxSize = 5,
  className,
  label = 'Image',
  shape = 'rectangle',
  aspectRatio
}: ImageUploadProps) {
  const t = useTranslations();
  const [preview, setPreview] = useState<string | null>(value || null);
  const [isDragging, setIsDragging] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
     setPreview(value || null);
  }, [value]);

  const handleFileChange = (file: File | null) => {
    if (!file) {
      setPreview(null);
      onChange(null, null);
      return;
    }

    // Validate file type
    if (!file.type.startsWith('image/')) {
      toast.error(t('validation.selectImageFile'));
      return;
    }

    // Validate file size
    if (file.size > maxSize * 1024 * 1024) {
      toast.error(t('validation.fileSizeExceeded', { maxSize }));
      return;
    }

    // Create preview
    const reader = new FileReader();
    reader.onloadend = () => {
      const previewUrl = reader.result as string;
      setPreview(previewUrl);
      onChange(file, previewUrl);
    };
    reader.readAsDataURL(file);
  };

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);

    const file = e.dataTransfer.files[0];
    handleFileChange(file);
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(true);
  };

  const handleDragLeave = () => {
    setIsDragging(false);
  };

  const handleClick = () => {
    fileInputRef.current?.click();
  };

  const handleRemove = () => {
    setPreview(null);
    onChange(null, null);
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  const shapeClasses = {
      square: 'w-48 h-48 rounded-lg',
      rectangle: 'w-full h-48 rounded-lg',
      circle: 'w-40 h-40 rounded-full'
  };

  return (
    <div className={cn('space-y-2', className)}>
      <Label>{label}</Label>
      
      <div
        className={cn(
          'relative flex cursor-pointer items-center justify-center border-2 border-dashed transition-colors overflow-hidden',
          shapeClasses[shape],
          aspectRatio,
          isDragging ? 'border-primary bg-primary/10' : 'border-gray-300 hover:border-primary',
          preview && 'border-solid'
        )}
        onDrop={handleDrop}
        onDragOver={handleDragOver}
        onDragLeave={handleDragLeave}
        onClick={handleClick}
      >
        <input
          ref={fileInputRef}
          type="file"
          accept="image/*"
          className="hidden"
          onChange={(e) => handleFileChange(e.target.files?.[0] || null)}
        />

        {preview ? (
          <>
            <img
              src={preview}
              alt="Preview"
              className={cn(
                "h-full w-full object-cover",
                 shape === 'circle' ? 'rounded-full' : 'rounded-lg'
              )}
            />
            <Button
              type="button"
              variant="destructive"
              size="icon"
              className="absolute -right-2 -top-2 h-8 w-8 rounded-full z-10"
              onClick={(e) => {
                e.stopPropagation();
                handleRemove();
              }}
            >
              <X className="h-4 w-4" />
            </Button>
          </>
        ) : (
          <div className="flex flex-col items-center gap-2 text-center p-4">
            <ImageIcon className="h-8 w-8 text-gray-400" />
            <div className="text-sm text-gray-500">
              <span className="font-medium text-primary">Click to upload</span>
              <br />
              or drag and drop
            </div>
            <p className="text-xs text-gray-400">PNG, JPG up to {maxSize}MB</p>
          </div>
        )}
      </div>
    </div>
  );
}
