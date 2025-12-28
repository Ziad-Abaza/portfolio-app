<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value',
        'is_active',
        'sort_order',
        'label_en',
        'description_en',
        'label_ar',
        'description_ar',
        'icon',
        'color',
        'prefix',
        'suffix',
        'metadata',
    ];

    protected $casts = [
        'value' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByKey(Builder $query, string $key): Builder
    {
        return $query->where('key', $key);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    public function scopeByValue(Builder $query, int $minValue = 0, int $maxValue = null): Builder
    {
        $query = $query->where('value', '>=', $minValue);
        
        if ($maxValue !== null) {
            $query = $query->where('value', '<=', $maxValue);
        }
        
        return $query;
    }

    // Accessors
    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"label_{$locale}"} ?? $this->label_en ?? '';
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en ?? '';
    }

    public function getFormattedValueAttribute(): string
    {
        $formattedValue = $this->prefix . number_format($this->value) . $this->suffix;
        return $formattedValue;
    }

    public function getFormattedValueWithoutSuffixAttribute(): string
    {
        return $this->prefix . number_format($this->value);
    }

    public function getRawValueAttribute(): int
    {
        return $this->value;
    }

    public function getDisplayValueAttribute(): string
    {
        switch ($this->type) {
            case 'percentage':
                return $this->value . $this->suffix;
            case 'number':
                return number_format($this->value) . $this->suffix;
            case 'counter':
                return $this->prefix . number_format($this->value) . $this->suffix;
            default:
                return $this->formattedValue;
        }
    }

    public function getIconClassAttribute(): string
    {
        return $this->icon ?? 'fa-chart-line';
    }

    public function getStyleAttribute(): string
    {
        return "color: {$this->color};";
    }

    // Methods
    public function getLocalizedContent(string $field): string
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

    public function incrementValue(int $amount = 1): bool
    {
        return $this->increment('value', $amount);
    }

    public function decrementValue(int $amount = 1): bool
    {
        return $this->decrement('value', $amount);
    }

    public function setValue(int $value): bool
    {
        return $this->update(['value' => $value]);
    }

    public function isPercentage(): bool
    {
        return $this->type === 'percentage';
    }

    public function isCounter(): bool
    {
        return $this->type === 'counter';
    }

    public function isNumber(): bool
    {
        return $this->type === 'number';
    }

    public function hasPrefix(): bool
    {
        return !empty($this->prefix);
    }

    public function hasSuffix(): bool
    {
        return !empty($this->suffix);
    }

    public function hasIcon(): bool
    {
        return !empty($this->icon);
    }

    public function hasColor(): bool
    {
        return !empty($this->color);
    }

    // Static methods
    public static function getActiveStatistics(): Builder
    {
        return static::active()->ordered();
    }

    public static function getStatisticsByType(string $type): Builder
    {
        return static::active()->byType($type)->ordered();
    }

    public static function findByKey(string $key): ?self
    {
        return static::active()->byKey($key)->first();
    }

    public static function incrementByKey(string $key, int $amount = 1): bool
    {
        $statistic = static::findByKey($key);
        
        if ($statistic) {
            return $statistic->incrementValue($amount);
        }
        
        return false;
    }

    public static function decrementByKey(string $key, int $amount = 1): bool
    {
        $statistic = static::findByKey($key);
        
        if ($statistic) {
            return $statistic->decrementValue($amount);
        }
        
        return false;
    }

    public static function setValueByKey(string $key, int $value): bool
    {
        $statistic = static::findByKey($key);
        
        if ($statistic) {
            return $statistic->setValue($value);
        }
        
        return false;
    }

    public static function getTotalValue(array $keys = []): int
    {
        $query = static::active();
        
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        
        return $query->sum('value');
    }

    public static function getAverageValue(array $keys = []): float
    {
        $query = static::active();
        
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        
        return $query->avg('value') ?? 0;
    }

    public static function getMaxValue(array $keys = []): int
    {
        $query = static::active();
        
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        
        return $query->max('value') ?? 0;
    }

    public static function getMinValue(array $keys = []): int
    {
        $query = static::active();
        
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        
        return $query->min('value') ?? 0;
    }

    // Route model binding
    public function getRouteKeyName(): string
    {
        return 'key';
    }
}
