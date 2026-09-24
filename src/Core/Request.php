<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    /** @var array<string,string> Route parameters captured by the router. */
    public array $params = [];

    private function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $query,
        public readonly array $body,
        public readonly array $files,
        public readonly array $headers,
        public readonly array $cookies,
    ) {
    }

    public static function capture(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        // Method spoofing for HTML forms (_method field), kept conservative.
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofed = strtoupper((string) $_POST['_method']);
            if (in_array($spoofed, ['PUT', 'PATCH', 'DELETE'], true)) {
                $method = $spoofed;
            }
        }

        $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $path = rawurldecode((string) (parse_url($uri, PHP_URL_PATH) ?: '/'));
        $path = '/' . trim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        $path = $path === '/' ? '/' : $path;

        $body = $_POST;
        $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        if ($method !== 'GET' && str_contains($contentType, 'application/json')) {
            $decoded = json_decode((string) file_get_contents('php://input'), true);
            $body = is_array($decoded) ? $decoded : [];
        }

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$name] = (string) $value;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = (string) $_SERVER['CONTENT_TYPE'];
        }

        return new self($method, $path, $_GET, $body, $_FILES, $headers, $_COOKIE);
    }

    /** Test seam — build a synthetic request without superglobals. */
    public static function fake(string $method = 'GET', string $path = '/', array $body = [], array $headers = [], array $query = []): self
    {
        return new self(strtoupper($method), '/' . ltrim($path, '/'), $query, $body, [], $headers, []);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function header(string $name, ?string $default = null): ?string
    {
        return $this->headers[strtolower($name)] ?? $default;
    }

    public function param(string $key, ?string $default = null): ?string
    {
        return $this->params[$key] ?? $default;
    }

    public function ip(): string
    {
        // No trusted-proxy awareness on purpose: portfolio has no LB.
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }

    public function isJson(): bool
    {
        return str_contains((string) $this->header('content-type', ''), 'application/json');
    }

    public function wantsJson(): bool
    {
        return str_contains((string) $this->header('accept', ''), 'application/json')
            || str_starts_with($this->path, '/api/');
    }
}
