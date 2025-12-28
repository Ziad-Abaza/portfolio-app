<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'category',
        'is_featured',
        'is_active',
        'sort_order',
        'completed_at',
        'title_en',
        'description_en',
        'content_en',
        'technologies_en',
        'challenges_en',
        'solutions_en',
        'title_ar',
        'description_ar',
        'content_ar',
        'technologies_ar',
        'challenges_ar',
        'solutions_ar',
        'github_url',
        'live_url',
        'demo_url',
        'images',
        'thumbnail_url',
        'tags',
        'metadata',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'completed_at' => 'date',
        'technologies_en' => 'array',
        'technologies_ar' => 'array',
        'challenges_en' => 'array',
        'challenges_ar' => 'array',
        'solutions_en' => 'array',
        'solutions_ar' => 'array',
        'images' => 'array',
        'tags' => 'array',
        'metadata' => 'array',
    ];

    // Boot method for automatic slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title_en);
            }
        });

        static::updating(function ($project) {
            if (empty($project->slug) || $project->isDirty('title_en')) {
                $project->slug = Str::slug($project->title_en);
            }
        });
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('completed_at', 'desc')->orderBy('id', 'desc');
    }

    // Accessors
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_en ?? '';
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en ?? '';
    }

    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"content_{$locale}"} ?? $this->content_en ?? '';
    }

    public function getTechnologiesAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"technologies_{$locale}"} ?? $this->technologies_en ?? [];
    }

    public function getChallengesAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"challenges_{$locale}"} ?? $this->challenges_en ?? [];
    }

    public function getSolutionsAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"solutions_{$locale}"} ?? $this->solutions_en ?? [];
    }

    // Methods
    public function getLocalizedContent(string $field): string|array
    {
        $locale = app()->getLocale();
        $localizedField = "{$field}_{$locale}";
        
        if (isset($this->{$localizedField}) && !empty($this->{$localizedField})) {
            return $this->{$localizedField};
        }
        
        // Fallback to English
        $englishField = "{$field}_en";
        return $this->{$englishField} ?? '';
    }

    public function getTechnologiesListAttribute(): array
    {
        return $this->technologies;
    }

    public function hasTechnology(string $technology): bool
    {
        return in_array($technology, $this->technologies);
    }

    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        return $this->thumbnail_url ?? $this->images[0] ?? '/images/projects/default.jpg';
    }

    public function getCompletionDateAttribute(): string
    {
        return $this->completed_at ? $this->completed_at->format('M Y') : 'Ongoing';
    }

    public function getUrlAttribute(): string
    {
        return $this->live_url ?? $this->demo_url ?? $this->github_url ?? '#';
    }

    public function getUrlTypeAttribute(): string
    {
        if ($this->live_url) return 'live';
        if ($this->demo_url) return 'demo';
        if ($this->github_url) return 'github';
        return 'none';
    }

    // Static methods
    public static function getActiveProjects(): Builder
    {
        return static::active()->ordered();
    }

    public static function getFeaturedProjects(): Builder
    {
        return static::active()->featured()->ordered();
    }

    public static function getProjectsByCategory(string $category): Builder
    {
        return static::active()->byCategory($category)->ordered();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::active()->bySlug($slug)->first();
    }

    public static function search(string $term): Builder
    {
        $locale = app()->getLocale();
        $titleField = "title_{$locale}";
        $descriptionField = "description_{$locale}";
        
        return static::active()
            ->where(function ($query) use ($term, $titleField, $descriptionField) {
                $query->where($titleField, 'like', "%{$term}%")
                      ->orWhere($descriptionField, 'like', "%{$term}%")
                      ->orWhere('tags', 'like', "%{$term}%");
            });
    }

    // Route model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function services()
    {
        return $this->belongsToMany(Service::class, 'project_service');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
    }

    // public function testimonials()
    // {
    //     return $this->hasMany(Testimonial::class);
    // }
}
