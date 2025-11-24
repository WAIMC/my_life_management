<?php

namespace App\Http\Resources\Management;

use Illuminate\Http\Resources\Json\JsonResource;

class GoogleDriveConfigResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'root_folder_id' => $this->root_folder_id,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'has_valid_credentials' => $this->hasValidCredentials(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            // Note: credentials_json is hidden by model
        ];
    }
}
