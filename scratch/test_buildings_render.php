<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate authenticated user
$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$controller = new \App\Http\Controllers\Buildings\BhmDashboardController();
$response = $controller->index();
$content = $response->render();

echo "Rendered Buildings Dashboard successfully! Length: " . strlen($content) . PHP_EOL;
