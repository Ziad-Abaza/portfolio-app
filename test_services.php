<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing services data...\n";

try {
    $services = \App\Models\Service::get();
    echo "Services count: " . $services->count() . "\n";
    
    foreach ($services as $service) {
        echo "- Icon: " . $service->icon . "\n";
        echo "  Title: " . $service->title . "\n";
        echo "  Description: " . substr($service->description, 0, 50) . "...\n";
        
        // Check if technologies_list is an array or string
        $techs = $service->technologies_list;
        if (is_array($techs)) {
            echo "  Technologies: " . implode(', ', array_slice($techs, 0, 3)) . "\n";
        } else {
            echo "  Technologies (raw): " . substr($techs, 0, 50) . "...\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
