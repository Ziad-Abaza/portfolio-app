<?php

/**
 * Test-state reset — clears rate limits and captured contact messages.
 * Local dev only: refuses to run when APP_ENV != local.
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

App\Core\Env::load(BASE_PATH . '/.env');
App\Core\Config::load(BASE_PATH . '/config');

if (App\Core\Config::get('app.env') !== 'local') {
    fwrite(STDERR, "Refused: test-reset only runs with APP_ENV=local\n");
    exit(1);
}

App\Core\DB::pdo()->exec('DELETE FROM rate_limits');
App\Core\DB::pdo()->exec('DELETE FROM contact_messages');
echo "test state reset\n";
