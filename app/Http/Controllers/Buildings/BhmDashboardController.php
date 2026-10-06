<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmLicenseForm;
use App\Models\Buildings\BhmEconomical;

class BhmDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buildings' => BhmBuilding::count(),
            'total_licenses'  => BhmLicenseForm::count(),
            'total_economical'=> BhmEconomical::count(),
            'historical'      => BhmBuilding::where('historical', 1)->count(),
        ];

        return view('buildings.dashboard', compact('stats'));
    }
}
