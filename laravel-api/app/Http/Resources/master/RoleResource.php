<?php

namespace App\Http\Resources\master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => (int)$this->id,
      'name' => (string)$this->name,
      'permission' => (string)$this->permission,
      'is_active' => (int)$this->is_active,
      'update_at' => (int)$this->update_at,
    ];
  }
}
