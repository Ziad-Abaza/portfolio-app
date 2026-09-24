<?php

declare(strict_types=1);

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\SpaController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\MediaController;
use App\Controllers\SeoController;
use App\Controllers\WorkController;
use App\Core\I18n;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

/** @var Router $router */

// Root → negotiated locale (paths are normalized without trailing slash)
$router->get('/', function (Request $req): Response {
    return Response::redirect('/' . I18n::negotiate((string) $req->header('accept-language', '')));
});

$router->get('/sitemap.xml', [SeoController::class, 'sitemap']);
$router->get('/robots.txt', [SeoController::class, 'robots']);
$router->get('/media/{id:\d+}/{name:.*}', [MediaController::class, 'show']);

// Localized public site
$router->group(['prefix' => '/{locale:[a-z]{2}}', 'middleware' => ['locale']], function (Router $r): void {
    $r->get('/', [HomeController::class, 'index']);
    $r->get('/work', [WorkController::class, 'index']);
    $r->get('/work/{slug}', [WorkController::class, 'show']);
    $r->get('/contact', [ContactController::class, 'show']);
    $r->post('/contact', [ContactController::class, 'submit'], ['csrf']);
});

// Admin — auth pages + SPA shell
$router->get('/admin/login', [AuthController::class, 'loginPage'], ['guest']);
$router->post('/admin/login', [AuthController::class, 'login'], ['guest', 'csrf']);
$router->post('/admin/logout', [AuthController::class, 'logout'], ['auth', 'csrf']);
$router->get('/admin', [SpaController::class, 'index'], ['auth']);
$router->get('/admin/{path:.+}', [SpaController::class, 'index'], ['auth']);

$router->fallback(function (Request $req): Response {
    return $req->wantsJson()
        ? Response::json(['error' => 'Not found'], 404)
        : Response::view('errors.404', [], 404);
});
