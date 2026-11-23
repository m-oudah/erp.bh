<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmEconomicalController;

Route::middleware(['auth', 'verified', 'module.access:crafts'])->prefix('crafts')->name('crafts.')->group(function () {
    Route::get('/', function () {
        $e = \App\Models\Buildings\BhmEconomical::query();

        $stats = [
            'total'           => (clone $e)->count(),
            // isActive: 2=نشط, 1=مغلق
            'active'          => (clone $e)->where('isActive', 2)->count(),
            'closed'          => (clone $e)->where('isActive', 1)->count(),
            // work_state: 0=جديد, 1=نشط/مستمر, null=غير محدد
            'new_unapproved'  => (clone $e)->whereNull('work_state')->count(),
            // isDanger: 1=خطرة, 0=عادية, 2=غير محدد
            'dangerous'       => (clone $e)->where('isDanger', 1)->count(),
            'safe'            => (clone $e)->where('isDanger', 0)->count(),
            // isLicensed: 1=مرخص, 2=غير مرخص
            'licensed'        => (clone $e)->where('isLicensed', 1)->count(),
            'unlicensed'      => (clone $e)->where('isLicensed', 2)->count(),
        ];

        $totalCrafts = \App\Models\Crafts\BhmCraft::count();

        // Top Sectors by economical count
        $topSectors = \App\Models\Buildings\BhmEconomical::select('job_sector_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->whereNotNull('job_sector_id')
            ->groupBy('job_sector_id')
            ->orderByDesc('count')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $sec = \App\Models\Buildings\BhmEconomicalSector::find($item->job_sector_id);
                $item->name = $sec ? ($sec->sector_name ?: $sec->name) : 'قطاع عام';
                $item->subname = $sec ? $sec->name : '';
                return $item;
            });

        // Top Activities for table
        $sectorStats = \App\Models\Buildings\BhmEconomical::select('job_sector_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->whereNotNull('job_sector_id')
            ->groupBy('job_sector_id')
            ->orderByDesc('count')
            ->take(6)
            ->get()
            ->map(function ($item) {
                $sec = \App\Models\Buildings\BhmEconomicalSector::find($item->job_sector_id);
                $item->sector_title = $sec ? ($sec->sector_name ?: 'عام') : 'عام';
                $item->activity_name = $sec ? $sec->name : 'نشاط تجاري/خدماتي';
                return $item;
            });

        // Recent Economical activities
        $recentEconomical = \App\Models\Buildings\BhmEconomical::with(['sector', 'category', 'owners', 'building'])
            ->latest()
            ->take(6)
            ->get();

        return view('crafts.dashboard', compact('stats', 'totalCrafts', 'topSectors', 'sectorStats', 'recentEconomical'));
    })->name('dashboard');
    
    // Economical Activities (Crafts)
    Route::get('/economical', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'index'])->name('economical.index')->middleware('can:Read-Crafts');
    Route::get('/economical/create', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'create'])->name('economical.create')->middleware('can:Create-Craft');
    Route::post('/economical', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'store'])->name('economical.store')->middleware('can:Create-Craft');
    Route::get('/economical/search-buildings', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'searchBuildings'])->name('economical.search-buildings');
    Route::get('/economical/search-citizens', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'searchCitizens'])->name('economical.search-citizens');
    Route::get('/economical/building-units/{buildingId}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'getBuildingUnits'])->name('economical.building-units');
    Route::get('/economical/{id}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'show'])->name('economical.show')->middleware('can:Read-Crafts');
    Route::get('/economical/{id}/edit', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'edit'])->name('economical.edit')->middleware('can:Update-Craft');
    Route::put('/economical/{id}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'update'])->name('economical.update')->middleware('can:Update-Craft');

    // Crafts
    Route::get('/crafts', [\App\Http\Controllers\Crafts\BhmCraftController::class, 'index'])->name('crafts.index')->middleware('can:Read-Crafts');
});
