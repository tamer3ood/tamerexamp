<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerCategoryServiceResource extends JsonResource
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
            'category_service_id'=>$this->category_service_id,
            'is_basic_pk'=>$this->is_basic_pk,
            'is_standard_pk'=>$this->is_standard_pk,
            'is_advanced_pk'=>$this->is_advanced_pk,
        ];
    }
}
