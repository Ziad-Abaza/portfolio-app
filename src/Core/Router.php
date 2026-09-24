<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use Throwable;

/**
 * Regex router with groups, named middleware and {param:constraint} syntax.
 * Handlers receive (Request) and return Response; params land on $request->params.
 */
final class Router
{
    private array $routes = [];
    private array $groupStack = [];

    /** @var array<string, callable(Request, Closure):Response> */
    private array $middleware = [];

    /** @var callable(Request):Response */
    private $notFoundHandler;

    public function middleware(string $name, callable $handler): void
    {
        $this->middleware[$name] = $handler;
    }

    public function get(string $uri, callable|array $handler, array $middleware = []): void
    {
        $this->add(['GET', 'HEAD'], $uri, $handler, $middleware);
    }

    public function post(string $uri, callable|array $handler, array $middleware = []): void
    {
        $this->add(['POST'], $uri, $handler, $middleware);
    }

    public function put(string $uri, callable|array $handler, array $middleware = []): void
    {
        $this->add(['PUT'], $uri, $handler, $middleware);
    }

    public function patch(string $uri, callable|array $handler, array $middleware = []): void
    {
        $this->add(['PATCH'], $uri, $handler, $middleware);
    }

    public function delete(string $uri, callable|array $handler, array $middleware = []): void
    {
        $this->add(['DELETE'], $uri, $handler, $middleware);
    }

    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    public function fallback(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(Request $request): Response
    {
        View::share('request', $request);
        View::share('requestPath', $request->path);
        foreach ($this->routes as $route) {
            if (!in_array($request->method, $route['methods'], true)) {
                continue;
            }
            $params = $this->match($route['pattern'], $request->path);
            if ($params === null) {
                continue;
            }
            $request->params = $params;
            return $this->pipeline($request, $route);
        }

        if (isset($this->notFoundHandler)) {
            return ($this->notFoundHandler)($request);
        }
        return Response::html('Not Found', 404);
    }

    private function add(array $methods, string $uri, callable|array $handler, array $middleware): void
    {
        foreach ($this->groupStack as $group) {
            $prefix = trim((string) ($group['prefix'] ?? ''), '/');
            $uri = '/' . trim(($prefix !== '' ? $prefix . '/' : '') . ltrim($uri, '/'), '/');
            $middleware = array_merge($group['middleware'] ?? [], $middleware);
        }
        $uri = $uri === '' ? '/' : $uri;
        $this->routes[] = [
            'methods' => $methods,
            'pattern' => $uri,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    /** @return array<string,string>|null */
    private function match(string $pattern, string $path): ?array
    {
        $names = [];
        $regex = preg_replace_callback(
            '/\{(\w+)(?::((?:[^{}]|\{[^{}]*\})*))?\}/',
            function (array $m) use (&$names): string {
                $names[] = $m[1];
                return '(' . ($m[2] ?? '[^/]+') . ')';
            },
            $pattern
        );
        if (!preg_match('#^' . $regex . '$#u', $path, $matches)) {
            return null;
        }
        array_shift($matches);
        return array_combine($names, $matches) ?: [];
    }

    private function pipeline(Request $request, array $route): Response
    {
        $handler = $route['handler'];
        $core = function (Request $req) use ($handler): Response {
            $result = is_array($handler)
                ? (new $handler[0]())->{$handler[1]}($req)
                : $handler($req);
            return $result instanceof Response ? $result : Response::html((string) $result);
        };

        foreach (array_reverse($route['middleware']) as $name) {
            $fn = $this->middleware[$name] ?? null;
            if ($fn === null) {
                throw new \RuntimeException("Unknown middleware: {$name}");
            }
            $next = $core;
            $core = fn (Request $req): Response => $fn($req, $next);
        }

        return $core($request);
    }
}
