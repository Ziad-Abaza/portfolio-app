<?php

return [
    // Empty dsn -> sqlite at 'path'. For MySQL: 'mysql:host=...;dbname=...;charset=utf8mb4'
    'dsn' => env('DB_DSN', ''),
    'path' => env('DB_PATH', 'database/portfolio.sqlite'),
    'user' => env('DB_USER'),
    'password' => env('DB_PASSWORD'),
];
