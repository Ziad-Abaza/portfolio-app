<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public function __construct(
        public string $body = '',
        public int $status = 200,
        public array $headers = [],
    ) {
    }

    public static function html(string $body, int $status = 200): self
    {
        return new self($body, $status, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    public static function json(mixed $data, int $status = 200): self
    {
        return new self(
            (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $status,
            ['Content-Type' => 'application/json; charset=UTF-8']
        );
    }

    public static function redirect(string $to, int $status = 302): self
    {
        // Open-redirect guard: only same-origin paths are allowed.
        if (!str_starts_with($to, '/')) {
            $to = '/';
        }
        return new self('', $status, ['Location' => $to]);
    }

    public static function view(string $template, array $data = [], int $status = 200, ?string $layout = 'layouts/site'): self
    {
        return self::html(View::render($template, $data, $layout), $status);
    }

    public static function download(string $path, string $name, string $mime): self
    {
        $response = new self((string) file_get_contents($path), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        return $response;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->body;
    }
}
