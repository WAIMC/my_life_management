export const MediaConst = {
  // File Types
  TYPE_FILE: true,
  TYPE_FOLDER: false,

  // Categories
  CATEGORY_IMAGE: 'image',
  CATEGORY_VIDEO: 'video',
  CATEGORY_DOCUMENT: 'document',
  CATEGORY_ARCHIVE: 'archive',
  CATEGORY_OTHER: 'other',

  // Max File Size (100MB)
  MAX_FILE_SIZE: 104857600,

  // Allowed Extensions
  ALLOWED_IMAGE_EXTENSIONS: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
  ALLOWED_VIDEO_EXTENSIONS: ['mp4', 'avi', 'mov', 'wmv', 'webm'],
  ALLOWED_AUDIO_EXTENSIONS: ['mp3', 'm4a', 'wav', 'ogg', 'flac', 'aac'],
  ALLOWED_DOCUMENT_EXTENSIONS: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
  ALLOWED_ARCHIVE_EXTENSIONS: ['zip', 'rar', '7z', 'tar', 'gz'],
} as const;

// File upload configuration
export const UPLOAD_CONFIG = {
  DEFAULT_ACCEPT: 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx',
  DEFAULT_MAX_SIZE: 50 * 1024 * 1024, // 50MB
  DEFAULT_IMAGE_MAX_SIZE: 5, // 5MB for images
  DEFAULT_AVATAR_MAX_SIZE: 5, // 5MB for avatars
  PROGRESS_INCREMENT: 10,
  PROGRESS_INTERVAL_MS: 200,
  MIN_PROGRESS: 0,
  MAX_PROGRESS: 90,
  PROGRESS_COMPLETE: 100,
  UPLOAD_COMPLETE_DELAY_MS: 500,
} as const;

// Image shape types
export const IMAGE_SHAPES = {
  SQUARE: 'square',
  RECTANGLE: 'rectangle',
  CIRCLE: 'circle',
} as const;

export type ImageShape = typeof IMAGE_SHAPES[keyof typeof IMAGE_SHAPES];

// Export/Import formats
export const EXPORT_FORMATS = {
  CSV: 'csv',
  EXCEL: 'excel',
  JSON: 'json',
} as const;

export type ExportFormat = typeof EXPORT_FORMATS[keyof typeof EXPORT_FORMATS];
export type ImportFormat = typeof EXPORT_FORMATS.CSV | typeof EXPORT_FORMATS.EXCEL;

// Drag and drop events
export const DRAG_EVENTS = {
  ENTER: 'dragenter',
  OVER: 'dragover',
  LEAVE: 'dragleave',
  DROP: 'drop',
} as const;

// Media manager configuration
export const MEDIA_MANAGER_CONFIG = {
  DEFAULT_PER_PAGE: 20,
  MIN_PAGE: 1,
} as const;

// File type filters
export const MEDIA_FILE_TYPES = ['all', 'images', 'videos', 'documents'] as const;

// MIME type prefixes
export const MIME_TYPE_PREFIX = {
  IMAGE: 'image/',
  VIDEO: 'video/',
  AUDIO: 'audio/',
  APPLICATION: 'application/',
} as const;
