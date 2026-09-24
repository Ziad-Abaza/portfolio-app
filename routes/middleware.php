<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\I18n;
use App\Core\Request;
use App\Core\Response;

/** @var App\Core\Router $router */

// Resolve and validate the locale prefix; unknown locales bounce to default.
$router->middleware('locale', function (Request $req, Closure $next): Response {
    $locale = (string) $req->param('locale', '');
    if (!in_array($locale, I18n::enabledLocales(), true)) {
        $rest = preg_replace('#^/' . preg_quote($locale, '#') . '#', '', $req->path) ?: '/';
        return Response::redirect('/' . I18n::defaultLocale() . $rest, 301);
    }
    I18n::setLocale($locale);
    return $next($req);
});

$router->middleware('csrf', function (Request $req, Closure $next): Response {
    if (in_array($req->method, ['POST', 'PUT', 'PATCH', 'DELETE'], true) && !Csrf::validate($req)) {
        return $req->wantsJson()
            ? Response::json(['error' => 'CSRF token mismatch'], 419)
            : Response::html('Session expired — go back and try again.', 419);
    }
    return $next($req);
});

$router->middleware('auth', function (Request $req, Closure $next): Response {
    return Auth::check() ? $next($req) : Response::redirect('/admin/login');
});

$router->middleware('auth.api', function (Request $req, Closure $next): Response {
    return Auth::check() ? $next($req) : Response::json(['error' => 'Unauthenticated'], 401);
});

$router->middleware('guest', function (Request $req, Closure $next): Response {
    return Auth::check() ? Response::redirect('/admin') : $next($req);
});
