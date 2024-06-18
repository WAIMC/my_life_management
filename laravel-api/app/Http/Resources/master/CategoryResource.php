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
      'id' => $this->id,
      'parent_id' => $this->parent_id,
      'name' => $this->name,
      'slug' => $this->slug,
      'description' => $this->description,
      'status' => $this->status,
      'is_display' => $this->is_display,
      'rank_order' => $this->rank_order,
    ];
  }
}
