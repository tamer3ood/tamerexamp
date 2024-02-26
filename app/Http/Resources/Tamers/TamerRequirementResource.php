<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerRequirementResource extends JsonResource
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
            'requirement_text' => $this->requirement_text,
            'answer_type' => $this->answer_type,
            'is_mandatory_requirement' => $this->is_mandatory_requirement,
            'is_allow_more_than_one_answer_for_multi_choice' => $this->is_allow_more_than_one_answer_for_multi_choice,
            'firstـchoice_text' => $this->firstـchoice_text,
            'secondـchoice_text' => $this->secondـchoice_text,
            'thirdـchoice_text' => $this->thirdـchoice_text,
            'fourthـchoice_text' => $this->fourthـchoice_text,
            'fifthـchoice_text' => $this->fifthـchoice_text,
            'sixthـchoice_text' => $this->sixthـchoice_text,

        ];
    }
}
