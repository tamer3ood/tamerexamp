<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerFileResource extends JsonResource
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
            'tamer_id'=>$this->tamer_id,
            'file_type'=>$this->file_type,
            'file_url'=>\Storage::disk('s3')->url('tamer_files/'. $this->file_url),
        ];
    }
}
