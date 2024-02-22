<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Tamer1Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'talent_id'=>$this->talent_id,
            'category_id'=>$this->category_id,
            'sub_category_id'=>$this->sub_category_id,
            'category_type_id'=>$this->category_type_id,
            'category_attribute_items'=>\App\Http\Resources\Categories\CategoryAttributeItemResource::collection($this->tamerCategoryAttributeItems),
           
        ];
    }
}
