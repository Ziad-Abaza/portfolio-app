<?php

declare(strict_types=1);

use App\Controllers\Api\AppearanceController;
use App\Controllers\Api\ContentController;
use App\Controllers\Api\MediaApiController;
use App\Controllers\Api\MessageController;
use App\Controllers\Api\OverviewController;
use App\Controllers\Api\ProjectApiController;
use App\Controllers\Api\SystemController;
use App\Core\Router;

/** @var Router $router */

$router->group(['prefix' => '/api/admin', 'middleware' => ['auth.api', 'csrf']], function (Router $r): void {
    // Dashboard
    $r->get('/overview', [OverviewController::class, 'index']);

    // Generic content resources: skills, timeline, metrics, socials, sections
    $r->get('/content/{resource:[a-z-]+}', [ContentController::class, 'index']);
    $r->post('/content/{resource:[a-z-]+}', [ContentController::class, 'store']);
    $r->put('/content/{resource:[a-z-]+}/{id:\d+}', [ContentController::class, 'update']);
    $r->delete('/content/{resource:[a-z-]+}/{id:\d+}', [ContentController::class, 'destroy']);
    $r->post('/content/{resource:[a-z-]+}/reorder', [ContentController::class, 'reorder']);

    // Projects + case-study blocks
    $r->get('/projects', [ProjectApiController::class, 'index']);
    $r->post('/projects', [ProjectApiController::class, 'store']);
    $r->get('/projects/{id:\d+}', [ProjectApiController::class, 'show']);
    $r->put('/projects/{id:\d+}', [ProjectApiController::class, 'update']);
    $r->delete('/projects/{id:\d+}', [ProjectApiController::class, 'destroy']);
    $r->post('/projects/{id:\d+}/blocks', [ProjectApiController::class, 'storeBlock']);
    $r->put('/blocks/{id:\d+}', [ProjectApiController::class, 'updateBlock']);
    $r->delete('/blocks/{id:\d+}', [ProjectApiController::class, 'destroyBlock']);

    // Appearance: themes + effects
    $r->get('/themes', [AppearanceController::class, 'themes']);
    $r->post('/themes', [AppearanceController::class, 'storeTheme']);
    $r->put('/themes/{id:\d+}', [AppearanceController::class, 'updateTheme']);
    $r->post('/themes/{id:\d+}/activate', [AppearanceController::class, 'activate']);
    $r->delete('/themes/{id:\d+}', [AppearanceController::class, 'destroyTheme']);
    $r->get('/effects', [AppearanceController::class, 'effects']);
    $r->put('/effects', [AppearanceController::class, 'updateEffects']);

    // System: seo, settings, locales, password
    $r->get('/seo', [SystemController::class, 'seoIndex']);
    $r->put('/seo', [SystemController::class, 'seoUpdate']);
    $r->get('/settings', [SystemController::class, 'settings']);
    $r->put('/settings', [SystemController::class, 'updateSettings']);
    $r->put('/password', [SystemController::class, 'updatePassword']);

    // Messages inbox
    $r->get('/messages', [MessageController::class, 'index']);
    $r->post('/messages/{id:\d+}/read', [MessageController::class, 'markRead']);
    $r->delete('/messages/{id:\d+}', [MessageController::class, 'destroy']);

    // Media library
    $r->get('/media', [MediaApiController::class, 'index']);
    $r->post('/media', [MediaApiController::class, 'store']);
    $r->delete('/media/{id:\d+}', [MediaApiController::class, 'destroy']);
});
