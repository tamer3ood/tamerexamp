<?php

namespace App\Http\Resources\Jobs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSkillResource extends JsonResource
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
            'title_en' => $this->title_en,
        ];
    }
}
