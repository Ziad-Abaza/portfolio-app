<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'icon',
        'sort_order',
        'title_en',
        'description_en',
        'technologies_en',
        'title_ar',
        'description_ar',
        'technologies_ar',
        'features_en',
        'features_ar',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'technologies_en' => 'array',
        'technologies_ar' => 'array',
        'features_en' => 'array',
        'features_ar' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Boot method for automatic slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title_en);
            }
        });

        static::updating(function ($service) {
            if (empty($service->slug) || $service->isDirty('title_en')) {
                $service->slug = Str::slug($service->title_en);
            }
        });
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
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

    public function getTechnologiesAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"technologies_{$locale}"} ?? $this->technologies_en ?? [];
    }

    public function getFeaturesAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"features_{$locale}"} ?? $this->features_en ?? [];
    }

    public function getTechnologiesListAttribute(): array
    {
        return $this->technologies;
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

    public function getFeaturesListAttribute(): string
    {
        $features = $this->features;
        return implode(' • ', $features);
    }

    public function hasTechnology(string $technology): bool
    {
        return in_array($technology, $this->technologies);
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features);
    }

    // Static methods
    public static function getActiveServices(): Builder
    {
        return static::active()->ordered();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::active()->bySlug($slug)->first();
    }

    // Route model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships (if needed in the future)
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_service');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'service_skill');
    }
}
