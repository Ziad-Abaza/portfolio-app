<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing database connection...\n";
echo "Database path: " . database_path('database.sqlite') . "\n";

try {
    // Test basic connection
    $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
    echo "Tables found:\n";
    foreach ($tables as $table) {
        echo "- " . $table->name . "\n";
    }
    
    // Test portfolio_content table specifically (correct singular name)
    echo "\nTesting portfolio_content table...\n";
    $portfolioContents = DB::select('SELECT COUNT(*) as count FROM portfolio_content');
    echo "Portfolio contents count: " . $portfolioContents[0]->count . "\n";
    
    // Test the PortfolioController model method
    echo "\nTesting PortfolioContent model...\n";
    $personalInfo = \App\Models\PortfolioContent::active()->byType('personal')->first();
    echo "Personal info found: " . ($personalInfo ? 'Yes' : 'No') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
