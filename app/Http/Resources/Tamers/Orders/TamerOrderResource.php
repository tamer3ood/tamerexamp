<?php

namespace App\Http\Resources\Tamers\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $user_status = "";

        // if (auth('api')->id() == $this->client_id) {
        //     $user_status = 'client';
        // } elseif (auth('api')->id() == $this->tamer_talent_id) {
        //     $user_status = 'talent';
        // }
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'tamer_talent_id' => $this->tamer_talent_id,
            'tamer_category_id' => $this->tamer_category_id,
            'tamer_sub_category_id' => $this->tamer_sub_category_id,
            'tamer_category_type_id' => $this->tamer_category_type_id,
            'tamer_id' => $this->tamer_id,
            'tamer_title' => $this->tamer_title,
            'tamer_description' => $this->tamer_description,
            'tamer_order_package_type' => $this->tamer_order_package_type,
            'tamer_package_title' => $this->tamer_package_title,
            'tamer_package_description' => $this->tamer_package_description,
            'tamer_package_price' => $this->tamer_package_price,
            'total_price' => $this->total_price,
            'tamer_order_category_attribute_items' => $this->tamerOrderCategoryAttributeItems,
            // 'user_status' => $user_status
        ];
    }
}
