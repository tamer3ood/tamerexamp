<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Tamer2Resource extends JsonResource
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
            'tamer_category_elements'=>TamerCategoryElementResource::collection($this->tamerCategoryElements),
            'tamer_category_services'=>TamerCategoryServiceResource::collection($this->tamerCategoryServices),
            'tamer_add_ons'=>TamerAddOnResource::collection($this->tamerAddOns),
        ];
    }
}
