<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate authenticated user with permissions
$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

// Make a GET request to /buildings
$request = \Illuminate\Http\Request::create('/buildings', 'GET');
$response = $app->handle($request);

echo "Buildings Dashboard HTTP Status: " . $response->getStatusCode() . PHP_EOL;
echo "Content length: " . strlen($response->getContent()) . PHP_EOL;

if ($response->getStatusCode() !== 200) {
    echo "Response error or redirect to: " . $response->headers->get('Location') . PHP_EOL;
}
