<?php

namespace App\Http\Resources\Jobs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobProposalResource extends JsonResource
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
            'job_id' => $this->job_id,
            'talent_id' => $this->talent_id,
            'price' => $this->price,
            'delivery_time' => $this->delivery_time,
            'description' => $this->description,
            'status' => $this->status,

        ];
    }
}
