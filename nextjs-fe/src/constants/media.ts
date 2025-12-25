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
