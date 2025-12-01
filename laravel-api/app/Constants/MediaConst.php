<?php

namespace App\Constants;

class MediaConst
{
    // File types
    public const TYPE_FILE = true;
    public const TYPE_FOLDER = false;

    // Default paths
    public const ROOT_PATH = '/';
    public const PATH_SEPARATOR = '/';

    // File categories by MIME
    public const CATEGORY_IMAGE = 'image';
    public const CATEGORY_VIDEO = 'video';
    public const CATEGORY_DOCUMENT = 'document';
    public const CATEGORY_ARCHIVE = 'archive';
    public const CATEGORY_OTHER = 'other';

    // Max file size (100MB)
    public const MAX_FILE_SIZE = 104857600;

    // Allowed extensions
    public const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    public const ALLOWED_VIDEO_EXTENSIONS = ['mp4', 'avi', 'mov', 'wmv', 'webm'];
    public const ALLOWED_DOCUMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
    public const ALLOWED_ARCHIVE_EXTENSIONS = ['zip', 'rar', '7z', 'tar', 'gz'];

    // Storage path patterns
    public const STORAGE_PATH_PATTERN = '{workspace}/{category}/{year}/{month}/{uuid}.{extension}';
}
