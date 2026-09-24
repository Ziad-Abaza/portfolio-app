<?php

declare(strict_types=1);

/**
 * CLI migration runner: php bin/migrate.php [--seed]
 * Tracks applied .sql files in the `migrations` table.
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use App\Core\Config;
use App\Core\DB;
use App\Core\Env;

Env::load(BASE_PATH . '/.env');
Config::load(BASE_PATH . '/config');

$pdo = DB::pdo();
$pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE NOT NULL,
    run_at TEXT NOT NULL DEFAULT (datetime(\'now\'))
)');

$applied = array_column(DB::fetchAll('SELECT name FROM migrations'), 'name');
$files = glob(BASE_PATH . '/database/migrations/*.sql') ?: [];
sort($files);

$ran = 0;
foreach ($files as $file) {
    $name = basename($file);
    if (in_array($name, $applied, true)) {
        continue;
    }
    $sql = (string) file_get_contents($file);
    DB::transaction(function () use ($pdo, $sql, $name): void {
        $pdo->exec($sql);
        DB::insert('migrations', ['name' => $name]);
    });
    echo "migrated  {$name}\n";
    $ran++;
}

echo $ran === 0 ? "nothing to migrate\n" : "done ({$ran} migration" . ($ran > 1 ? 's' : '') . ")\n";

if (in_array('--seed', $argv, true)) {
    require BASE_PATH . '/database/seed.php';
}
