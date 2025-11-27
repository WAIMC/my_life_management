<?php

namespace App\Http\Resources\Management;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'admin_mst_id' => $this->admin_mst_id,
            'google_file_id' => $this->google_file_id,
            'original_name' => $this->original_name,
            'extension' => $this->extension,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'human_size' => $this->formatFileSize($this->size),
            'folder_path' => $this->folder_path,
            'is_public' => $this->is_public,
            'metadata' => $this->metadata,
            'status' => $this->status,
            'view_url' => route('api.media-files.view', ['id' => $this->id]),
            'download_url' => route('api.media-files.download', ['id' => $this->id]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes > 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
