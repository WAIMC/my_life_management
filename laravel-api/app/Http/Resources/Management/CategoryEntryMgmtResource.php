<?php

namespace App\Http\Resources\Management;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryEntryMgmtResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'category_mgmt_id' => (int)$this->category_mgmt_id,
      'entry_mgmt_id' => (int)$this->entry_mgmt_id,
      'category' => new CategoryMgmtResource($this->whenLoaded('category')),
      'entry' => new EntryMgmtResource($this->whenLoaded('entry')),
      'updated_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->updated_at)),
    ];
  }
}
