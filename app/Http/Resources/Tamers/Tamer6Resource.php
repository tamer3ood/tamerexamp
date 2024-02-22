<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Tamer6Resource extends JsonResource
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
            'term_of_service_agreement'=>$this->term_of_service_agreement,
            'privacy_notice_agreement'=>$this->privacy_notice_agreement,
            'max_no_of_simultaneous_tamers'=>$this->max_no_of_simultaneous_tamers,
        ];
    }
}
