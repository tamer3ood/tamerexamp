<?php

namespace App\Http\Resources\Jobs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $user_type = 'visitor';

        if (auth('api')->id() == $this->talent_id && auth('api')->id() != null) {
            $user_type = 'talent';
        } else if (auth('api')->id() == $this->client_id && auth('api')->id() != null) {
            $user_type = 'client';
        } else {
            $user_type = 'visitor';
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'category_type_id' => $this->category_type_id,
            'client_id' => $this->client_id,
            'project_manager_id' => $this->project_manager_id,
            'maximum_budget' => $this->maximum_budget,
            'description' => $this->description,
            'status' => $this->status,
            'user_type' => $user_type,
            'job_category_attribute_items' => JobCategoryAttributeItemResource::collection($this->jobCategoryAttributeItems),
            // 'job_category_attribute_items'=>$this->jobCategoryAttributeItems,
            'job_category_services' => JobCategoryServiceResource::collection($this->jobCategoryServices),
            'job_category_elements' => JobCategoryElementResource::collection($this->jobCategoryElements),

            'job_skills' => JobSkillResource::collection($this->jobSkills),
            'job_files' => JobFileResource::collection($this->jobFiles),
            'proposals' => JobProposalResource::collection($this->proposals),
            'total_proposals' => $this->proposals->count(),
        ];
    }
}
