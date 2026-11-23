<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$urls = [
    '/buildings',
    '/buildings/buildings',
    '/buildings/units',
    '/buildings/licenses',
    '/buildings/streets',
    '/crafts',
    '/crafts/economical',
    '/crafts/crafts',
];

foreach ($urls as $url) {
    $req = \Illuminate\Http\Request::create($url, 'GET');
    $res = $app->handle($req);
    echo "{$url} -> Status: " . $res->getStatusCode() . " (Size: " . strlen($res->getContent()) . " bytes)\n";
}
