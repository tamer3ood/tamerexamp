<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Tamer4Resource extends JsonResource
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
            'tamer_requirements'=>TamerRequirementResource::collection($this->tamerRequirements),
            
        ];
    }
}
