<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'category' => $this->category,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'completed_at' => $this->completed_at,
            'github_url' => $this->github_url,
            'live_url' => $this->live_url,
            'demo_url' => $this->demo_url,
            'thumbnail_url' => $this->thumbnail_url,
            'images' => $this->images,
            'tags' => $this->tags,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Localized content
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'technologies' => $this->technologies,
            'challenges' => $this->challenges,
            'solutions' => $this->solutions,
            
            // Raw multilingual data (for admin editing)
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'content_en' => $this->content_en,
            'content_ar' => $this->content_ar,
            'technologies_en' => $this->technologies_en,
            'technologies_ar' => $this->technologies_ar,
            'challenges_en' => $this->challenges_en,
            'challenges_ar' => $this->challenges_ar,
            'solutions_en' => $this->solutions_en,
            'solutions_ar' => $this->solutions_ar,
            
            // Computed properties
            'technologies_list' => $this->technologies_list,
            'primary_image_url' => $this->primary_image_url,
            'completion_date' => $this->completion_date,
            'url' => $this->url,
            'url_type' => $this->url_type,
            
            // Relationships (if loaded)
            'services' => $this->whenLoaded('services'),
            'skills' => $this->whenLoaded('skills'),
            'testimonials' => $this->whenLoaded('testimonials'),
        ];
    }
}
