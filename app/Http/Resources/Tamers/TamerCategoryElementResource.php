<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerCategoryElementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $is_delivery_days = false;
        $is_number_of_revisions = false;
        if ($this->categoryElement->title_en === 'Delivery Days') {
            $is_delivery_days = true;
        } elseif ($this->categoryElement->title_en === 'Number of Revisions') {
            $is_number_of_revisions = true;
        } else {
            $is_delivery_days = false;
            $is_number_of_revisions = false;
        }
        return [
            'id' => $this->id,
            'tamer_id' => $this->tamer_id,
            'category_element_id' => $this->category_element_id,
            // 'category_element_title_en' => $this->deployments->title_en,
            'basic_pk_element_value' => $this->basic_pk_element_value,
            'standard_pk_element_value' => $this->standard_pk_element_value,
            'advanced_pk_element_value' => $this->advanced_pk_element_value,
            'category_element_title_en' => $this->categoryElement->title_en,
            'is_delivery_days' => $is_delivery_days,
            'is_number_of_revisions' => $is_number_of_revisions,
        ];
    }
}
