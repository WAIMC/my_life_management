import {
  File,
  FileText,
  FileImage,
  FileVideo,
  FileAudio,
  FileArchive,
  FileCode,
  Folder,
} from 'lucide-react';
import type { MediaFile, FilterOptions, SortOptions } from './types';

export const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return '0 B';

  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));

  return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};

export const getFileIcon = (mimeType: string | null | undefined) => {
  // Handle null/undefined mime_type (e.g., for folders)
  if (!mimeType) {
    return File;
  }
  
  if (mimeType === 'folder' || mimeType === 'application/vnd.google-apps.folder') return Folder;
  if (mimeType.startsWith('image/')) return FileImage;
  if (mimeType.startsWith('video/')) return FileVideo;
  if (mimeType.startsWith('audio/')) return FileAudio;
  if (
    mimeType.includes('pdf') ||
    mimeType.includes('word') ||
    mimeType.includes('sheet') ||
    mimeType.includes('document')
  ) {
    return FileText;
  }
  if (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('compressed')) {
    return FileArchive;
  }
  if (mimeType.startsWith('text/') || mimeType.includes('json') || mimeType.includes('xml')) return FileCode;

  return File;
};

export const getMimeTypeLabel = (mimeType: string | null | undefined): string => {
  // Handle null/undefined mime_type (e.g., for folders)
  if (!mimeType) {
    return 'Folder';
  }
  
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
    'application/vnd.google-apps.folder': 'Folder',
    'folder': 'Folder',
  };

  return types[mimeType] || mimeType.split('/')[1]?.toUpperCase() || 'File';
};

export const filterFiles = (
  files: MediaFile[],
  searchQuery: string,
  options: FilterOptions,
) => {
  return files.filter((file) => {
    // Search query
    if (searchQuery && !file.name.toLowerCase().includes(searchQuery.toLowerCase())) {
      return false;
    }

    // Type filter
    if (options.type !== 'all') {
      if (options.type === 'folders' && file.type !== 'folder') return false;
      if (options.type === 'images' && (!file.mime_type || !file.mime_type.startsWith('image/'))) return false;
      if (options.type === 'videos' && (!file.mime_type || !file.mime_type.startsWith('video/'))) return false;
      if (options.type === 'documents' && (file.type === 'folder' || !file.mime_type || file.mime_type.startsWith('image/') || file.mime_type.startsWith('video/'))) return false;
    }

    // Date filter
    if (options.dateFrom && new Date(file.created_at) < options.dateFrom) return false;
    if (options.dateTo && new Date(file.created_at) > options.dateTo) return false;

    // Size filter
    if (options.minSize && file.size < options.minSize) return false;
    if (options.maxSize && file.size > options.maxSize) return false;

    return true;
  });
};

export const sortFiles = (
  files: MediaFile[],
  options: SortOptions,
) => {
  const sorted = [...files];
  const { field, order } = options;
  const multiplier = order === 'asc' ? 1 : -1;

  sorted.sort((a, b) => {
    switch (field) {
      case 'name':
        return a.name.localeCompare(b.name) * multiplier;
      case 'date':
        return (new Date(a.created_at).getTime() - new Date(b.created_at).getTime()) * multiplier;
      case 'size':
        return (a.size - b.size) * multiplier;
      case 'type':
        return a.mime_type.localeCompare(b.mime_type) * multiplier;
      default:
        return 0;
    }
  });

  return sorted;
};

export const isValidFileName = (name: string): boolean => {
  // Check for invalid characters (Windows/Unix common restrictions)
  const invalidChars = /[<>:"/\\|?*]/;
  return name.length > 0 && name.length <= 255 && !invalidChars.test(name);
};

export const buildBreadcrumb = (path: string) => {
  const parts = path.split('/').filter(Boolean);
  return parts.map((part, index) => ({
    label: part,
    path: '/' + parts.slice(0, index + 1).join('/'),
  }));
};
