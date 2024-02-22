<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Tamer3Resource extends JsonResource
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
            'tamer_files'=> TamerFileResource::collection($this->tamerFiles),
        ];
    }
}
