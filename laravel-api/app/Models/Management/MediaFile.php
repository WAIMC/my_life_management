<?php

namespace App\Models\Management;

use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    use HasSoftDelete, HasStatus;

    protected $table = 'media_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_mst_id',
        'google_file_id',
        'original_name',
        'extension',
        'mime_type',
        'size',
        'folder_path',
        'is_public',
        'metadata',
        'status',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'admin_mst_id' => 'integer',
        'google_file_id' => 'string',
        'original_name' => 'string',
        'extension' => 'string',
        'mime_type' => 'string',
        'size' => 'integer',
        'folder_path' => 'string',
        'is_public' => 'boolean',
        'metadata' => 'array',
        'status' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin user who uploaded the file.
     *
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\AdminMst::class, 'admin_mst_id');
    }

    /**
     * Get the file URL for viewing.
     *
     * @return string
     */
    public function getViewUrlAttribute(): string
    {
        return route('api.media-files.view', ['id' => $this->id]);
    }

    /**
     * Get the file URL for downloading.
     *
     * @return string
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('api.media-files.download', ['id' => $this->id]);
    }

    /**
     * Get human-readable file size.
     *
     * @return string
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
