<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Compact validator. Rules: required, string, int, numeric, bool, array,
 * email, url, min:N, max:N, in:a,b,c, nullable, json, slug, locale.
 */
final class Validator
{
    private array $errors = [];

    private function __construct(
        private readonly array $data,
        private readonly array $rules,
    ) {
        $this->run();
    }

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    /** Only the fields covered by rules. */
    public function validated(): array
    {
        return array_intersect_key($this->data, $this->rules);
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleSet) {
            $rules = is_array($ruleSet) ? $ruleSet : explode('|', (string) $ruleSet);
            $value = $this->data[$field] ?? null;
            $nullable = in_array('nullable', $rules, true);

            if ($value === null || $value === '') {
                if (in_array('required', $rules, true)) {
                    $this->errors[$field][] = 'required';
                }
                continue; // nullable or absent optional fields skip the rest
            }

            foreach ($rules as $rule) {
                if (in_array($rule, ['required', 'nullable'], true)) {
                    continue;
                }
                [$name, $arg] = array_pad(explode(':', (string) $rule, 2), 2, null);
                $ok = match ($name) {
                    'string' => is_string($value),
                    'int' => filter_var($value, FILTER_VALIDATE_INT) !== false,
                    'numeric' => is_numeric($value),
                    'bool' => in_array($value, [true, false, 0, 1, '0', '1'], true),
                    'array' => is_array($value),
                    'email' => is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
                    'url' => is_string($value) && filter_var($value, FILTER_VALIDATE_URL) !== false,
                    'json' => is_string($value) && json_decode($value, true) !== null,
                    'slug' => is_string($value) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value) === 1,
                    'locale' => is_string($value) && preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $value) === 1,
                    'min' => is_array($value) ? count($value) >= (int) $arg : mb_strlen((string) $value) >= (int) $arg,
                    'max' => is_array($value) ? count($value) <= (int) $arg : mb_strlen((string) $value) <= (int) $arg,
                    'in' => in_array($value, explode(',', (string) $arg), true),
                    default => true,
                };
                if (!$ok) {
                    $this->errors[$field][] = $arg === null ? $name : "{$name}:{$arg}";
                }
            }
        }
    }
}
