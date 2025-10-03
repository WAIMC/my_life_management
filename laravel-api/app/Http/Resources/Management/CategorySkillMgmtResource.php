<?php

namespace App\Http\Resources\Management;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySkillMgmtResource extends JsonResource
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
            'skill_mgmt_id' => (int)$this->skill_mgmt_id,
            'updated_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->updated_at)),
        ];
    }
}
