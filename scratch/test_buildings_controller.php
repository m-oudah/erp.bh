<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmZone;
use App\Models\Buildings\BhmBuildingType;
use Illuminate\Support\Facades\DB;

$topBuildingTypes = BhmBuilding::select('building_type_id', DB::raw('count(*) as count'))
    ->whereNotNull('building_type_id')
    ->groupBy('building_type_id')
    ->orderByDesc('count')
    ->take(5)
    ->get()
    ->map(function ($item) {
        $type = BhmBuildingType::find($item->building_type_id);
        $item->name = $type ? $type->name : 'غير محدد';
        return $item;
    });

echo "Top Building Types:\n";
foreach ($topBuildingTypes as $t) {
    echo "- {$t->name}: {$t->count}\n";
}

$zoneStats = BhmBuilding::select('zone_id', DB::raw('count(*) as count'))
    ->whereNotNull('zone_id')
    ->groupBy('zone_id')
    ->orderByDesc('count')
    ->take(5)
    ->get();

$zones = BhmZone::pluck('zone_name', 'id');
echo "\nTop Zones:\n";
foreach ($zoneStats as $zs) {
    $zName = $zones[$zs->zone_id] ?? ('حي رقم ' . $zs->zone_id);
    echo "- {$zName}: {$zs->count}\n";
}

$recentBuildings = BhmBuilding::with(['zone', 'street', 'owners', 'buildingType'])->latest()->take(3)->get();
echo "\nRecent Buildings: " . $recentBuildings->count() . "\n";
