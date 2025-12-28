<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'category',
        'proficiency_level',
        'years_experience',
        'is_active',
        'sort_order',
        'name_en',
        'description_en',
        'keywords_en',
        'certifications_en',
        'name_ar',
        'description_ar',
        'keywords_ar',
        'certifications_ar',
        'icon',
        'color',
        'projects_count',
    ];

    protected $casts = [
        'proficiency_level' => 'integer',
        'years_experience' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'keywords_en' => 'array',
        'keywords_ar' => 'array',
        'certifications_en' => 'array',
        'certifications_ar' => 'array',
        'projects_count' => 'array',
    ];

    // Boot method for automatic slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name_en);
            }
        });

        static::updating(function ($skill) {
            if (empty($skill->slug) || $skill->isDirty('name_en')) {
                $skill->slug = Str::slug($skill->name_en);
            }
        });
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
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
        return $query->orderBy('sort_order', 'asc')->orderBy('proficiency_level', 'desc');
    }

    public function scopeByProficiency(Builder $query, int $minLevel = 0, int $maxLevel = 100): Builder
    {
        return $query->whereBetween('proficiency_level', [$minLevel, $maxLevel]);
    }

    public function scopeExpert(Builder $query): Builder
    {
        return $query->where('proficiency_level', '>=', 80);
    }

    public function scopeIntermediate(Builder $query): Builder
    {
        return $query->whereBetween('proficiency_level', [50, 79]);
    }

    public function scopeBeginner(Builder $query): Builder
    {
        return $query->where('proficiency_level', '<', 50);
    }

    // Accessors
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_en ?? '';
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en ?? '';
    }

    public function getKeywordsAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"keywords_{$locale}"} ?? $this->keywords_en ?? [];
    }

    public function getCertificationsAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"certifications_{$locale}"} ?? $this->certifications_en ?? [];
    }

    public function getProficiencyLevelTextAttribute(): string
    {
        $level = $this->proficiency_level;
        
        if ($level >= 90) return __('portfolio.expert');
        if ($level >= 70) return __('portfolio.advanced');
        if ($level >= 50) return __('portfolio.intermediate');
        if ($level >= 30) return __('portfolio.beginner');
        return __('portfolio.novice');
    }

    public function getExperienceLevelAttribute(): string
    {
        $years = $this->years_experience;
        
        if ($years >= 5) return __('portfolio.senior');
        if ($years >= 3) return __('portfolio.mid_level');
        if ($years >= 1) return __('portfolio.junior');
        return __('portfolio.entry_level');
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

    public function getKeywordsListAttribute(): string
    {
        $keywords = $this->keywords;
        return implode(', ', $keywords);
    }

    public function hasKeyword(string $keyword): bool
    {
        return in_array($keyword, $this->keywords);
    }

    public function hasCertification(string $certification): bool
    {
        return in_array($certification, $this->certifications);
    }

    public function getProjectCountAttribute(): int
    {
        return $this->projects_count['total'] ?? 0;
    }

    public function getRecentProjectsCountAttribute(): int
    {
        return $this->projects_count['recent'] ?? 0;
    }

    public function isExpert(): bool
    {
        return $this->proficiency_level >= 80;
    }

    public function isIntermediate(): bool
    {
        return $this->proficiency_level >= 50 && $this->proficiency_level < 80;
    }

    public function isBeginner(): bool
    {
        return $this->proficiency_level < 50;
    }

    // Static methods
    public static function getActiveSkills(): Builder
    {
        return static::active()->ordered();
    }

    public static function getSkillsByCategory(string $category): Builder
    {
        return static::active()->byCategory($category)->ordered();
    }

    public static function getExpertSkills(): Builder
    {
        return static::active()->expert()->ordered();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::active()->bySlug($slug)->first();
    }

    public static function getCategories(): array
    {
        return static::active()->distinct()->pluck('category')->toArray();
    }

    public static function search(string $term): Builder
    {
        $locale = app()->getLocale();
        $nameField = "name_{$locale}";
        $descriptionField = "description_{$locale}";
        
        return static::active()
            ->where(function ($query) use ($term, $nameField, $descriptionField) {
                $query->where($nameField, 'like', "%{$term}%")
                      ->orWhere($descriptionField, 'like', "%{$term}%")
                      ->orWhere('keywords_en', 'like', "%{$term}%")
                      ->orWhere('keywords_ar', 'like', "%{$term}%");
            });
    }

    // Route model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_skill');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_skill');
    }
}
