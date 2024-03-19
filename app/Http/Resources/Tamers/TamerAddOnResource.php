<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerAddOnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category_eleserv_title_en = '';
        if ($this->add_on_type == 'categoryService') {
            $category_eleserv_title_en = $this->CategoryService->title_en;
        } else {
            $category_eleserv_title_en = $this->CategoryElement->title_en;
        }
        return [
            'id' => $this->id,
            'add_on_type' => $this->add_on_type,
            'category_eleserv_id' => $this->category_eleserv_id,
            'category_eleserv_title_en' => $category_eleserv_title_en,
            'additional_days' => $this->additional_days,
            'extra_price' => $this->extra_price,
            'title_for_custom_add_on' => $this->title_for_custom_add_on,
            'description_for_custom_add_on' => $this->description_for_custom_add_on,
        ];
    }
}
