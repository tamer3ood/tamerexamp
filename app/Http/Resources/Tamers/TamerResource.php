<?php

namespace App\Http\Resources\Tamers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TamerResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'talent_id' => $this->talent_id,
            'category_id' => $this->category_id,
            'category_title' => $this->category->title_en,
            'sub_category_id' => $this->sub_category_id,
            'sub_category_title' => $this->subCategory->title_en,
            'category_type_id' => $this->category_type_id,
            // 'category_type_title' => $this->category_type,
            'category_attribute_items' => \App\Http\Resources\Categories\CategoryAttributeItemResource::collection($this->tamerCategoryAttributeItems),
            'basic_package_price' => $this->basic_package_price,
            'standard_package_price' => $this->standard_package_price,
            'advanced_package_price' => $this->advanced_package_price,
            'tamer_category_elements' => TamerCategoryElementResource::collection($this->tamerCategoryElements),
            'tamer_category_services' => TamerCategoryServiceResource::collection($this->tamerCategoryServices),
            'tamer_add_ons' => TamerAddOnResource::collection($this->tamerAddOns),
            'tamer_files' => TamerFileResource::collection($this->tamerFiles),
            'tamer_requirements' => TamerRequirementResource::collection($this->tamerRequirements),
            'description' => $this->description,
            'tamer_steps' => TamerStepResource::collection($this->tamerSteps),
            'tamer_questions' => TamerQuestionResource::collection($this->tamerQuestions),

        ];
    }
}
