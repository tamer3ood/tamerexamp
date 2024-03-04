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
            'id' => $this->id,
            'title' => $this->title,
            'is_basic' => $this->has_basic_package,
            'is_standard' => $this->has_standard_package,
            'is_advanced' => $this->has_advanced_package,
            'basic_package_price' => $this->basic_package_price,
            'standard_package_price' => $this->standard_package_price,
            'advanced_package_price' => $this->advanced_package_price,
            'tamer_category_elements' => TamerCategoryElementResource::collection($this->tamerCategoryElements),
            'tamer_category_services' => TamerCategoryServiceResource::collection($this->tamerCategoryServices),
            'tamer_add_ons' => TamerAddOnResource::collection($this->tamerAddOns),


        ];
    }
}
