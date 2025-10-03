<?php

namespace App\Http\Resources\History\Management;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingLinkMgmtHistResource extends JsonResource
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
            'setting_link_id' => (int)$this->setting_link_id,
            'key' => (string)$this->key,
            'value' => (string)$this->value,
            'action' => (string)$this->action,
            'author_id' => (int)$this->author_id,
            'created_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->created_at)),
        ];
    }
}
