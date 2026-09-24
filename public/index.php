<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Env;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\SecurityHeaders;
use App\Core\Session;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

Env::load(BASE_PATH . '/.env');
Config::load(BASE_PATH . '/config');

$debug = config('app.debug') === true;
if ($debug) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', storage_path('logs/php-error.log'));
}

set_exception_handler(function (Throwable $e) use ($debug): void {
    $line = sprintf(
        "[%s] %s in %s:%d\n%s\n",
        date('c'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );
    @file_put_contents(storage_path('logs/app.log'), $line, FILE_APPEND | LOCK_EX);

    $response = $debug
        ? Response::html('<pre style="padding:2rem;font:14px monospace;white-space:pre-wrap">' . e((string) $e) . '</pre>', 500)
        : Response::view('errors.500', [], 500);
    SecurityHeaders::apply($response);
    $response->send();
});

Session::start();
$request = Request::capture();
$router = new Router();

require BASE_PATH . '/routes/middleware.php';
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';

$response = $router->dispatch($request);
SecurityHeaders::apply($response);
$response->send();
