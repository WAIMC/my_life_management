<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'         => (int)$this->id,
      'name'       => (string)$this->name,
      'group_name' => (string)$this->group_name,
      'status'     => (int)$this->status,
      'update_at'  => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
