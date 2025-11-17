import {
  File,
  FileText,
  FileImage,
  FileVideo,
  FileAudio,
  FileArchive,
  FileCode,
} from 'lucide-react';
import type { MediaFile } from './types';

export const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return '0 B';

  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));

  return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};

export const getFileIcon = (mimeType: string) => {
  if (mimeType.startsWith('image/')) return FileImage;
  if (mimeType.startsWith('video/')) return FileVideo;
  if (mimeType.startsWith('audio/')) return FileAudio;
  if (
    mimeType.includes('pdf') ||
    mimeType.includes('word') ||
    mimeType.includes('sheet')
  ) {
    return FileText;
  }
  if (mimeType.includes('zip') || mimeType.includes('rar')) {
    return FileArchive;
  }
  if (mimeType.startsWith('text/')) return FileCode;

  return File;
};

export const getMimeTypeLabel = (mimeType: string): string => {
  const types: Record<string, string> = {
    'image/jpeg': 'JPEG',
    'image/png': 'PNG',
    'image/gif': 'GIF',
    'image/webp': 'WebP',
    'video/mp4': 'MP4',
    'video/webm': 'WebM',
    'audio/mpeg': 'MP3',
    'application/pdf': 'PDF',
    'application/zip': 'ZIP',
  };

  return types[mimeType] || mimeType.split('/')[1]?.toUpperCase() || 'File';
};

export const filterFiles = (
  files: MediaFile[],
  searchQuery: string,
  filterType: string,
) => {
  return files.filter((file) => {
    const matchesSearch = file.name
      .toLowerCase()
      .includes(searchQuery.toLowerCase());
    const matchesType =
      filterType === 'all' || file.mime_type.startsWith(filterType);

    return matchesSearch && matchesType;
  });
};

export const sortFiles = (
  files: MediaFile[],
  sortBy: 'name' | 'date' | 'size' | 'type',
) => {
  const sorted = [...files];

  switch (sortBy) {
    case 'name':
      sorted.sort((a, b) => a.name.localeCompare(b.name));
      break;
    case 'date':
      sorted.sort(
        (a, b) =>
          new Date(b.created_at).getTime() - new Date(a.created_at).getTime(),
      );
      break;
    case 'size':
      sorted.sort((a, b) => b.size - a.size);
      break;
    case 'type':
      sorted.sort((a, b) => a.mime_type.localeCompare(b.mime_type));
      break;
  }

  return sorted;
};
