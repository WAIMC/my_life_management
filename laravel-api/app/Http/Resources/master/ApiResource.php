<?php

namespace App\Http\Resources\master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'            => (int)$this->id,
      'type_name'     => (string)$this->type_name,
      'type'          => (int)$this->type,
      'name'          => (string)$this->name,
      'path'          => (string)$this->path,
      'is_valid'      => (bool)$this->is_valid,
      'feature_id'    => (int)$this->feature_id,
      'feature_name'  => (string)$this->feature_name,
      'feature_group' => (string)$this->feature_group,
      'updated_at'     => (string)$this->updated_at,
    ];
  }
}
