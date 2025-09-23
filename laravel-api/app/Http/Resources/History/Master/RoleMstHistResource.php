<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleMstHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role_mst_id' => $this->role_mst_id,
            'role' => $this->when($this->relationLoaded('role'), function () {
                return $this->role ? [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                ] : null;
            }),
            'name' => $this->name,
            'permission' => $this->permission,
            'is_active' => (bool) $this->is_active,
            'action' => $this->action,
            'action_name' => $this->action_name,
            'author_id' => $this->author_id,
            'author' => $this->when($this->relationLoaded('author'), function () {
                return $this->author ? [
                    'id' => $this->author->id,
                    'name' => $this->author->first_name . ' ' . $this->author->last_name,
                ] : null;
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
