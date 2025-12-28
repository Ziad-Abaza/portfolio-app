<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'slug' => $this->slug,
            'icon' => $this->icon,
            'image_url' => $this->image_url,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Localized content
            'title' => $this->title,
            'description' => $this->description,
            'technologies' => $this->technologies,
            'features' => $this->features,
            
            // Raw multilingual data (for admin editing)
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'technologies_en' => $this->technologies_en,
            'technologies_ar' => $this->technologies_ar,
            'features_en' => $this->features_en,
            'features_ar' => $this->features_ar,
            
            // Computed properties
            'technologies_list' => $this->technologies_list,
            'features_list' => $this->features_list,
            
            // Relationships (if loaded)
            'projects' => $this->whenLoaded('projects'),
            'skills' => $this->whenLoaded('skills'),
        ];
    }
}
