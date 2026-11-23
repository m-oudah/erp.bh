<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\Buildings\BhmEconomical;

print_r(Schema::getColumnListing('bhm_economical'));

$sample = BhmEconomical::with(['sector', 'category', 'owners', 'building'])->first();
if ($sample) {
    echo "Sample economical:\n";
    print_r($sample->toArray());
}
