<?php

declare(strict_types=1);

/**
 * Test bootstrap — isolated SQLite database, real kernel.
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

App\Core\Env::load(BASE_PATH . '/.env');
// Point the DB at a throwaway test database AFTER env load
// (Env::load would otherwise overwrite it with the real path).
App\Core\Env::set('DB_PATH', 'database/test.sqlite');
App\Core\Config::load(BASE_PATH . '/config');

$testDb = BASE_PATH . '/database/test.sqlite';
if (is_file($testDb)) {
    unlink($testDb);
}

// Run migrations + seed on the test database.
$pdo = App\Core\DB::pdo();
$pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE NOT NULL,
    run_at TEXT NOT NULL DEFAULT (datetime(\'now\'))
)');
foreach (glob(BASE_PATH . '/database/migrations/*.sql') ?: [] as $file) {
    $pdo->exec((string) file_get_contents($file));
    App\Core\DB::insert('migrations', ['name' => basename($file)]);
}
require BASE_PATH . '/database/seed.php';

// CLI session shim — Session helpers operate on $_SESSION directly.
$_SESSION = $_SESSION ?? [];
