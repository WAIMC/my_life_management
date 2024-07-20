<?php

namespace App\Http\Resources\master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
      'parent_id' => (int)$this->parent_id,
      'name' => (string)$this->name,
      'slug' => (string)$this->slug,
      'description' => (string)$this->description,
      'status' => (int)$this->status,
      'is_display' => (bool)$this->is_display,
      'rank_order' => (int)$this->rank_order,
    ];
  }
}
