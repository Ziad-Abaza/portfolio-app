<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

/**
 * Native PHP templates. Views are plain .php files in resources/views,
 * dot-notation names, explicit layout wrapping. No magic: the template
 * receives $data extracted + helpers from src/helpers.php.
 */
final class View
{
    private static array $shared = [];

    /** Data available to every view (e.g. the current request path). */
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function render(string $template, array $data = [], ?string $layout = 'layouts/site'): string
    {
        $content = self::partial($template, $data);
        if ($layout !== null) {
            return self::partial($layout, $data + ['content' => $content]);
        }
        return $content;
    }

    public static function partial(string $template, array $data = []): string
    {
        $file = base_path('resources/views/' . str_replace('.', '/', $template) . '.php');
        if (!is_file($file)) {
            throw new RuntimeException("View not found: {$template}");
        }
        extract(self::$shared + $data, EXTR_SKIP);
        ob_start();
        try {
            require $file;
            return (string) ob_get_clean();
        } catch (Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
}
