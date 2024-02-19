<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryAttributeResource extends JsonResource
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
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'is_required' => $this->is_required,
            'max_no_of_items' => $this->max_no_of_items,
            'items' => CategoryAttributeItemResource::collection($this->categoryAttributeItems)

        ];
    }
}
