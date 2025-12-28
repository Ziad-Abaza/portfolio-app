<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PortfolioContent extends Model
{
    use HasFactory;

    protected $table = 'portfolio_content';

    protected $fillable = [
        'key',
        'type',
        'language',
        'title_en',
        'subtitle_en',
        'description_en',
        'metadata_en',
        'title_ar',
        'subtitle_ar',
        'description_ar',
        'metadata_ar',
        'is_active',
        'sort_order',
        'category',
    ];

    protected $casts = [
        'metadata_en' => 'array',
        'metadata_ar' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage(Builder $query, string $language): Builder
    {
        return $query->where('language', $language);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    // Accessors
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_en ?? '';
    }

    public function getSubtitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"subtitle_{$locale}"} ?? $this->subtitle_en ?? '';
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en ?? '';
    }

    public function getMetadataAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"metadata_{$locale}"} ?? $this->metadata_en ?? [];
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

    public static function getContentByKey(string $key, string $language = null): ?self
    {
        $language = $language ?? app()->getLocale();
        
        return static::active()
            ->byKey($key)
            ->byLanguage($language)
            ->first();
    }

    public static function getContentByType(string $type, string $language = null): Builder
    {
        $language = $language ?? app()->getLocale();
        
        return static::active()
            ->byType($type)
            ->byLanguage($language)
            ->ordered();
    }

    // Query scope for key
    public function scopeByKey(Builder $query, string $key): Builder
    {
        return $query->where('key', $key);
    }
}
