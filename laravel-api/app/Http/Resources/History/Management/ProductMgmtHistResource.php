<?php

namespace App\Http\Resources\History\Management;

use App\Enums\ActionType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMgmtHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_mgmt_id' => $this->product_mgmt_id,
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'code' => $this->product->code,
                ];
            }),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ] : null;
            }),
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'is_display' => (bool)$this->is_display,
            'rank_order' => $this->rank_order,
            'action' => $this->action,
            'action_label' => isset($this->action) ? ActionType::getLabel($this->action) : null,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
        ];
    }
}
