import { SortField, ViewMode, FilterType, FileType, FileOperationType, SortOrder, MoveCopyMode } from '@/shared/types/file-manager.types';

export const SORT_FIELD: Record<string, SortField> = {
  NAME: 'name',
  DATE: 'date',
  SIZE: 'size',
  TYPE: 'type',
} as const;

export const SORT_ORDER: Record<string, SortOrder> = {
  ASC: 'asc',
  DESC: 'desc',
} as const;

export const DEFAULT_SORT_FIELD: SortField = SORT_FIELD.NAME;

export const VIEW_MODE: Record<string, ViewMode> = {
  GRID: 'grid',
  LIST: 'list',
} as const;

export const DEFAULT_VIEW_MODE: ViewMode = VIEW_MODE.GRID;

export const FILTER_TYPE: Record<string, FilterType> = {
  ALL: 'all',
  IMAGES: 'images',
  VIDEOS: 'videos',
  DOCUMENTS: 'documents',
  FOLDERS: 'folders',
} as const;

export const DEFAULT_FILTER_TYPE: FilterType = FILTER_TYPE.ALL;

export const FILE_TYPE: Record<string, FileType> = {
  FILE: 'file',
  FOLDER: 'folder',
} as const;

export const DATE_FORMAT = {
  SHORT: 'dd/MM/yy',
  MEDIUM: 'dd/MM/yyyy',
  LONG: 'dd/MM/yyyy HH:mm',
  FULL: 'dd/MM/yyyy HH:mm:ss',
} as const;

export const OPERATION_TYPE: Record<string, FileOperationType> = {
  RENAME: 'rename',
  MOVE: 'move',
  COPY: 'copy',
  DELETE: 'delete',
  UPLOAD: 'upload',
  CREATE_FOLDER: 'create_folder',
} as const;

export const MOVE_COPY_MODE: Record<string, MoveCopyMode> = {
  MOVE: 'move',
  COPY: 'copy',
} as const;

export const TRANSLATION_KEY = {
  MOVE_FAILED: 'moveFailed',
  COPY_FAILED: 'copyFailed',
  MOVE_SUCCESS: 'moveSuccess',
  COPY_SUCCESS: 'copySuccess',
} as const;

export const KEYBOARD_SHORTCUT = {
  ENTER: 'Enter',
  F2: 'F2',
  DELETE: 'Del',
  ESCAPE: 'Escape',
  ARROW_LEFT: 'ArrowLeft',
  ARROW_RIGHT: 'ArrowRight',
  ARROW_UP: 'ArrowUp',
  ARROW_DOWN: 'ArrowDown',
} as const;

export const KEYBOARD_EVENT = {
  KEYDOWN: 'keydown',
  KEYUP: 'keyup',
  KEYPRESS: 'keypress',
} as const;

export const UI_CONFIG = {
  LOADING_SKELETON_COUNT: 10,
} as const;

export const DEFAULT_SIDEBAR_FOLDERS = [
  { id: '1', nameKey: 'sidebar.images', path: '/images/2025' },
  { id: '2', nameKey: 'sidebar.videos', path: '/videos' },
  { id: '3', nameKey: 'sidebar.documents', path: '/documents' },
  { id: '4', nameKey: 'sidebar.recent', path: '/recent' },
  { id: '5', nameKey: 'sidebar.shared', path: '/shared' },
] as const;

export const FILE_SIZE_UNITS = ['B', 'KB', 'MB', 'GB'] as const;

export const MIME_TYPE_LABELS: Record<string, string> = {
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
} as const;

export const INITIAL_PAGINATION = {
  page: 1,
  pageSize: 20,
  total: 0,
  totalPages: 1,
} as const;
