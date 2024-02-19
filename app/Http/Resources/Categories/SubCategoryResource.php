<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'parent' => new CategoryResource($this->parentCategory),
            'has_own_attribute' => $this->has_own_attribute,
            // 'attributes' => CategoryAttributeResource::collection($this->categoryAttributes),
            // 'elements' => CategoryElementResource::collection($this->categoryElements),
            // 'services' => CategoryServiceResource::collection($this->categoryServices),
        ];
    }
}
